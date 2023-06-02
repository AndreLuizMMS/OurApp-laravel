<x-profile :sharedData="$sharedData">
  <div class="list-group">
    @foreach ($userFollows as $follow)
      <a href="/profile/{{ $follow->userGettingFollowedBy->username }}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="/storage/default.png" />
        <strong> {{ $follow->userGettingFollowedBy->username }}</strong>
      </a>
    @endforeach
  </div>
</x-profile>
