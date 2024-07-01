<x-layout.main>

    <div class="box">
        <form action="{{ route('tasks.tasks.store', $parent) }}" method="POST">
            @csrf
            <h1 class="title is-4">Add a New Task to {{ $parent->title }}</h1>
            <br>
            <h2 class="subtitle is-6 is-italic">
                Please fill out all the form fields and click 'Submit'
            </h2>

            {{-- Here are all the form fields --}}
            <label for="title" class="label">Title</label>
            <div class="control has-icons-right">
                <input type="text" name="title" placeholder="Enter the post's title..."
                       class="input @error('title') is-danger @enderror"
                       value="{{ old('title') }}" autocomplete="title" autofocus>
                @error('title')
                <span class="icon has-text-danger is-small is-right">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                @enderror
            </div>
            @error('title')
            <p class="help is-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="field">
        <label for="description" class="label">Description</label>
        <div class="control has-icons-right">
            <x-ui.wysiwyg name="description" height="240" class="@error('description') is-danger @enderror"
                          placeholder="Enter the task's description..."
                          value="{{ old('description') }}" ></x-ui.wysiwyg>
            @error('description')
            <span class="icon has-text-danger is-small is-right">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
            @enderror
        </div>
        @error('description')
        <p class="help is-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="field is-grouped">
        <div class="control">
            <button type="submit" class="button is-primary">Save</button>
        </div>
        <div class="control">
            <a type="button" href="{{ url()->previous() }}" class="button is-light">Cancel</a>
        </div>
        <div class="control">
            <button type="reset" class="button is-warning">Reset</button>
        </div>
    </div>
    </form>
    </div>
</x-layout.main>
