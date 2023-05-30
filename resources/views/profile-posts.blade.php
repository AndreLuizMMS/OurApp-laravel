<x-layout>
  <div class="container py-md-5 container--narrow">
    <h2>
      <img class="avatar-small" src="{{ auth()->user()->getAvatar }}" />
      {{ $user->username }}
      @auth
        @if ($alreadyFollows)
          <form class="ml-2 d-inline" action="/unfollow/{{ $user->username }}" method="POST">
            @csrf
            <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
            @if (auth()->user()->username == $user->username)
              <a href="/manage-avatar" class="btn btn-secondary btn-sm">
                Manage Avatar</a>
            @endif
          </form>
        @elseif(auth()->user()->username == $user->username)
          <a href="/manage-avatar" class="btn btn-secondary btn-sm ml-2">
            Manage Avatar
          </a>
        @else
          <form class="ml-2 d-inline" action="/follow/{{ $user->username }}" method="POST">
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
      <a href="#" class="profile-nav-link nav-item nav-link active">Posts: {{ count($posts) }}</a>
      <a href="#" class="profile-nav-link nav-item nav-link">Followers: 3</a>
      <a href="#" class="profile-nav-link nav-item nav-link">Following: 2</a>
    </div>

    <div class="list-group">
      @foreach ($posts as $post)
        <a href="/post/{{ $post->id }}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src={{ $user->getAvatar }} />
          <strong>{{ $post->title }}</strong> on {{ $post->created_at->format('d/m/Y') }}
        </a>
      @endforeach
    </div>
  </div>
</x-layout>
