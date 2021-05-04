<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Test
        </h2>
    </x-slot>

    <div class="container mt-5">
        <form action="{{route('fileUpload')}}" method="post" enctype="multipart/form-data">
            <h3 class="text-center mb-5">Upload File in Laravel</h3>
            @csrf
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="custom-file">
                <input type="file" name="file" class="custom-file-input" id="chooseFile">
                <label class="custom-file-label" for="chooseFile">Select file</label>
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
                Upload Files
            </button>
        </form>
    </div>

    <div>
        <table>
            @foreach($attachments as $file)
                <tr>
                    <td>{{$file->id}}</td>
                    <td>
                        <a href="{{config('app.url') . $file->path}}" target="_blank" download>______{{$file->name}}</a>
                    </td>
                    <td>
                        <a href="{{url('download', ['id' => $file->id])}}">______{{$file->name}}</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

</x-app-layout>
