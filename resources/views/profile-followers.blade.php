<x-profile :sharedData="$sharedData">
  <div class="list-group">
    @foreach ($userFollowers as $follow)
      <a href="/profile/{{ $follow->userIsFollowing->username }}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="/storage/avatars/{{ $follow->userIsFollowing->avatar }}" />
        <strong>{{ $follow->userIsFollowing->username }}</strong>
      </a>
    @endforeach
  </div>
</x-profile>
