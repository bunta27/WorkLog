<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CorrectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_clock_in' => 'required|date_format:H:i|before:new_clock_out',
            'new_clock_out' => 'required|date_format:H:i|after:new_clock_in',

            'new_break_in' => 'nullable|array',
            'new_break_out' => 'nullable|array',

            'new_break_in.*' => 'nullable|date_format:H:i|before:new_clock_out|after:new_clock_in',
            'new_break_out.*' => 'nullable|date_format:H:i|before:new_clock_out',

            'comment' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'new_clock_in.required' => '出勤時間を入力してください',
            'new_clock_in.date_format' => '出勤時間は「HH:MM」の形式で入力してください',
            'new_clock_in.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'new_clock_out.required' => '退勤時間を入力してください',
            'new_clock_out.date_format' => '退勤時間は「HH:MM」の形式で入力してください',
            'new_clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',

            'new_break_in.array' => '休憩時間が不適切な値です',
            'new_break_in.*.date_format'  => '休憩開始時間は「HH:MM」の形式で入力してください',
            'new_break_in.date_format' => '休憩開始時間は「HH:MM」の形式で入力してください',
            'new_break_out.array' => '休憩時間が不適切な値です',
            'new_break_out.*.date_format' => '休憩終了時間は「HH:MM」の形式で入力してください',
            'new_break_out.date_format' => '休憩終了時間は「HH:MM」の形式で入力してください',

            'new_break_in.*.before' => '休憩時間が不適切な値です',
            'new_break_in.*.after' => '休憩時間が不適切な値です',
            'new_break_out.*.before' => '休憩時間もしくは退勤時間が不適切な値です',

            'comment.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $ins  = (array) $this->input('new_break_in', []);
        $outs = (array) $this->input('new_break_out', []);

        foreach ($ins as $i => $in) {
            $out = $outs[$i] ?? null;

            if ($in && $out) {
                try {
                    $inTime = \Carbon\Carbon::createFromFormat('H:i', $in);
                    $outTime = \Carbon\Carbon::createFromFormat('H:i', $out);

                    if ($outTime->lte($inTime)) {
                        $validator->errors()->add("new_break_out.$i", '休憩終了は休憩開始より後の時刻を入力してください。');
                    }
                } catch (\Exception $e) {
                }
            }
        }
    });
}

}
