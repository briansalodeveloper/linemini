<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $isRegister = empty($this->id);

        $rtn = [
            'name' => 'required|string|min:2|max:128',
            'username' => ['required', 'string', 'min:2', 'max:32'],
            'email' => ['regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', 'max:191'],
            'role' => 'required|integer',
        ];

        $passwordValidation = [
            'required',
            'min:4',
            'max:16',
        ];

        if ($isRegister) {
            $rtn['username'][] = 'unique:M_Admin,username';
            $rtn['email'][] = 'unique:M_Admin,email';
            $rtn['password'] = $passwordValidation;

            if (!empty($this->password)) {
                $rtn['passwordConfirmation'] = 'required|same:password';
            }
        } else {
            $rtn['email'][] = 'unique:M_Admin,username,' . $this->id;
            $rtn['email'][] = 'unique:M_Admin,email,' . $this->id;

            if ($this->updatePassword) {
                $rtn['password'] = $passwordValidation;

                if (!empty($this->password)) {
                    $rtn['passwordConfirmation'] = 'required|same:password';
                }
            }
        }

        if (empty($this->email)) {
            unset($rtn['email']);
        }
        
        return $rtn;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $email = $this->email;
        $password = $this->password;
        $updatePassword = $this->updatePassword;

        if (empty($email)) {
            $email = '';
        }

        if (empty($updatePassword)) {
            $updatePassword = false;
        }

        $this->merge([
            'email' => $email,
            'pw' => $password,
            'updatePassword' => $updatePassword,
        ]);

        request()->merge($this->all());
    }
}
