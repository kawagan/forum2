<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Reply;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  Gate::allows('create',new Reply); // or !Gate::denies('create',new Reply);
    }

    protected function failedAuthorization()
     {
        // return response('You are positng too frequently. Please take break',422);
        throw new \Exception('You are positng too frequently. Please take break',422);
        
     }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body'=>'required|spamfree' // spam free explained in replyController
        ];
    }
}
