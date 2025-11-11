        <div id="head">
            <h1><p class="btn"><a href="{{ route('top') }}"><img src="{{ asset('images/atlas.png') }}"></a></p></h1>
            <div id="profile-area">
               <div class="user-display">
                  <p id="username-display">{{ Auth::user()->username }}さん</p>
                  <div class="accordion-header">
               <span class="accordion-arrow"></span>
             </div>
             <div class="accordion-content">
                    <ul>
                    <li><a href="{{ route('top') }}">HOME</a></li>
                    <li><a href="{{ route('profile.edit') }}">プロフィール編集</a></li>
                    <li><form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
                     </form>
               <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a></li>
                   </ul>
               </div>
                  <img src="{{ asset('images/' . (Auth::user()->icon_image)) }}"class="header-user-icon">
               </div>
           </div>
        </div>
