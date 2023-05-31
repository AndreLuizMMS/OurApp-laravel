<x-profile :sharedData="$sharedData">

  <div class="list-group">

    @foreach ($followers as $user)
      {{ $user->userFollowedBy }}
      <p>followers</p>
    @endforeach
  </div>
</x-profile>
