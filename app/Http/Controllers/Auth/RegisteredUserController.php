<?php /*ユーザー登録プロセス*/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /*登録フォーム表示するメソッド*/
    public function create(): View
    {
        return view('auth.register'); /*表示するbladeファイル*/
    }

    public function store(Request $request): RedirectResponse /*登録後の処理*/
    {
        $request->validate([
            'username' => ['required','string','min:2', 'max:12'],
            'email' => ['required','unique:users','email','min:5','max:40'],
            'password' => ['required','alpha_num','min:8', 'max:20','confirmed'],
      ]);
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Session::put('username', $request->username); /*情報保存*/

        return redirect()->route('added');
    }

    public function added(): View /*登録完了後の画面*/
    {
        $username = Session::get('username'); /*保存したデータを取り出す*/
        return view('auth.added', compact('username'));
    }
}
