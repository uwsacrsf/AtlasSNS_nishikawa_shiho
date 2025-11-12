<?php /*ログアウトさせるプロセス*/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LogoutController extends Controller
{

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout(); /*非ログイン状態にさせる*/

        $request->session()->invalidate(); /*セッション無効化*/
        $request->session()->regenerateToken(); /*新しいトークン作成*/

        return redirect('/login');
    }
}
