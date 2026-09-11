<?php

namespace App\Http\Controllers;

use App\Models\AiInteraction;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiAgentController extends Controller
{
    private function callClaude(string $system, string $prompt, string $model = 'claude-haiku-4-5'): array
    {
        $start    = microtime(true);
        $response = Http::withHeaders([
            'x-api-key'         => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => $model,
            'max_tokens' => 1024,
            'system'     => $system,
            'messages'   => [['role' => 'user', 'content' => $prompt]],
        ]);
        $json = $response->json();
        return [
            'text'          => $json['content'][0]['text'] ?? '',
            'input_tokens'  => $json['usage']['input_tokens'] ?? 0,
            'output_tokens' => $json['usage']['output_tokens'] ?? 0,
            'duration_ms'   => (int)((microtime(true) - $start) * 1000),
            'model'         => $model,
            'success'       => $response->successful(),
        ];
    }

    public function suggestNames(Request $request)
    {
        $data = $request->validate(['idea' => ['required','string','max:300'], 'industry' => ['nullable','string','max:100']]);
        $system = 'You are a Kenyan business naming expert. Respond ONLY with valid JSON, no markdown. Format: {"names":[{"name":"...","meaning":"...","why":"..."}]}';
        $result = $this->callClaude($system, "Suggest 6 business names for: {$data['idea']}. Industry: {$data['industry']}");
        AiInteraction::create(['user_id'=>Auth::id(),'agent'=>'name_suggester','prompt_summary'=>"Names for: {$data['idea']}",'response_summary'=>substr($result['text'],0,200),'model_used'=>$result['model'],'input_tokens'=>$result['input_tokens'],'output_tokens'=>$result['output_tokens'],'duration_ms'=>$result['duration_ms'],'was_successful'=>$result['success']]);
        $names = json_decode($result['text'], true)['names'] ?? [];
        $existing = Company::whereIn('company_name', array_column($names, 'name'))->pluck('company_name')->toArray();
        foreach ($names as &$n) { $n['available'] = !in_array($n['name'], $existing, true); }
        return response()->json(['names' => $names]);
    }

    public function writeDescription(Request $request)
    {
        $data = $request->validate(['company_name'=>['required','string'],'business_type'=>['required','string'],'industry'=>['required','string'],'services'=>['required','string','max:500'],'location'=>['nullable','string']]);
        $system = 'You are a professional business writer for Kenyan entrepreneurs. Write clear 80-150 word directory descriptions. Return only the description text.';
        $prompt = "Company: {$data['company_name']}\nType: {$data['business_type']}\nIndustry: {$data['industry']}\nServices: {$data['services']}\nLocation: {$data['location']}";
        $result = $this->callClaude($system, $prompt);
        AiInteraction::create(['user_id'=>Auth::id(),'agent'=>'description_writer','prompt_summary'=>"Description for: {$data['company_name']}",'response_summary'=>substr($result['text'],0,200),'model_used'=>$result['model'],'input_tokens'=>$result['input_tokens'],'output_tokens'=>$result['output_tokens'],'duration_ms'=>$result['duration_ms'],'was_successful'=>$result['success']]);
        return response()->json(['description' => trim($result['text'])]);
    }

    public function complianceGuide(Request $request)
    {
        $data = $request->validate(['business_type'=>['required','string'],'county'=>['required','string'],'has_employees'=>['required','boolean'],'question'=>['required','string','max:500']]);
        $system = 'You are a Kenyan business compliance assistant. Help entrepreneurs understand post-registration obligations. You are NOT a lawyer. You know about KRA PIN, VAT, SHIF, NSSF, county permits, and BRS annual returns.';
        $emp    = $data['has_employees'] ? 'Yes' : 'No';
        $prompt = "Business type: {$data['business_type']}\nCounty: {$data['county']}\nHas employees: {$emp}\nQuestion: {$data['question']}";
        $result = $this->callClaude($system, $prompt, 'claude-sonnet-4-6');
        AiInteraction::create(['user_id'=>Auth::id(),'agent'=>'compliance_guide','prompt_summary'=>"Compliance Q: {$data['question']}",'response_summary'=>substr($result['text'],0,200),'model_used'=>$result['model'],'input_tokens'=>$result['input_tokens'],'output_tokens'=>$result['output_tokens'],'duration_ms'=>$result['duration_ms'],'was_successful'=>$result['success']]);
        return response()->json(['answer' => $result['text']]);
    }
}