<x-layout :docTitle="$sharedData['user']->username">
  <div class="container py-md-5 container--narrow">
    <h2>
      <img class="avatar-small" src="{{ auth()->user()->getAvatar }}" />
      {{ $sharedData['user']->username }}
      @auth
        @if ($sharedData['alreadyFollows'])
          <form class="ml-2 d-inline" action="/unfollow/{{ $sharedData['user']->username }}" method="POST">
            @csrf
            <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
            @if (auth()->user()->username == $sharedData['user']->username)
              <a href="/manage-avatar" class="btn btn-secondary btn-sm">
                Manage Avatar</a>
            @endif
          </form>
        @elseif(auth()->user()->username == $sharedData['user']->username)
          <a href="/manage-avatar" class="btn btn-secondary btn-sm ml-2">
            Manage Avatar
          </a>
        @else
          <form class="ml-2 d-inline" action="/follow/{{ $sharedData['user']->username }}" method="POST">
            @csrf
            <button class="btn btn-primary btn-sm">
              Follow
              <i class="fas fa-user-plus">
                {{-- follow button --}}
              </i>
            </button>
          </form>
        @endif
      @endauth
    </h2>

    <div class="profile-nav nav nav-tabs pt-2 mb-4">
      <a href="/profile/{{ $sharedData['user']->username }}"
        class="profile-nav-link nav-item nav-link {{ Request::segment(3) == '' ? 'active' : '' }}">
        Posts: {{ $sharedData['postCount'] }}
      </a>
      <a href="/profile/{{ $sharedData['user']->username }}/followers"
        class="profile-nav-link nav-item nav-link {{ Request::segment(3) == 'followers' ? 'active' : '' }}">
        Followers: {{ $sharedData['followersCount'] }}
      </a>
      <a href="/profile/{{ $sharedData['user']->username }}/following"
        class="profile-nav-link nav-item nav-link {{ Request::segment(3) == 'following' ? 'active' : '' }}">
        Following: {{ $sharedData['followingCount'] }}
      </a>
    </div>

    <div class="profile-slot-content">
      {{ $slot }}
    </div>

  </div>
</x-layout>
