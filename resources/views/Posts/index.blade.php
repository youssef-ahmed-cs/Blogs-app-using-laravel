@extends('layouts.app')

@section('title') Index @endsection
@section('content')
    <div class="text-center mb-4">
        <a href="{{route('posts.create')}}" class="btn btn-success">Create Post</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Posted By</th>
                <th scope="col">Created At</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($postsFromDB as $post)
                <tbody>
                <tr>
                    <td>{{$post->id}}</td>
                    <td>{{$post->title}}</td>
                    <td>{{$post->user_creator ? $post->user_creator->name : 'User Not Founf'}}</td>
                    <td>{{$post->created_at->format('Y-m-d')}}</td>
                    <td>
                        <a class="btn btn-info btn-sm w-25" href="{{route('posts.show',$post['id'])}}">View</a>
                        <a class="btn btn-primary btn-sm w-25" href="{{route('posts.edit',$post['id'])}}">Edit</a>

                        <form method="POST" action="{{ route('posts.destroy', $post['id']) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger w btn-sm w-25 "  type="submit" href="{{route('posts.index')}}">Delete</button>
                        </form>

                    </td>

                </tr>
                @endforeach
                </tbody>
        </table>
    </div>
</div>

</body>
</html>
@endsection
