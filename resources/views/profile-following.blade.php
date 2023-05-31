<x-profile :sharedData="$sharedData">

  <div class="list-group">
    @foreach ($userFollows as $user)
      {{ $user->userFollows }}
    @endforeach
  </div>
</x-profile>
