<x-layout.main>
    <div class="navbar">
        <div class="navbar-start">
            <div class="block">
                <h1 class="title is-4">
                    {{ $post->title }}
                </h1>
                <div class="tags">
                    <span class="tag has-text-weight-bold">
                        {{ $post->state }}
                    </span>
                </div>
            </div>
        </div>
        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <a href="{{ route('posts.edit', $post) }}" class="button is-primary">Edit</a>
                    <x-ui.modal name="delete" title="Confirm delete" type="danger">
                        <x-slot:trigger class="is-danger">Delete</x-slot:trigger>

                        <form id="delete-post" method="POST" action="{{ route('posts.destroy', $post) }}">
                            @csrf
                            @method('DELETE')
                            Click "Confirm" to delete this Post.
                            <br>
                            <strong>CAUTION!</strong> This action cannot be undone.
                        </form>

                        <x-slot:footer>
                            <div class="control">
                                <button type="submit" form="delete-post" class="button is-danger">Confirm</button>
                            </div>
                            <div class="control">
                                <button type="button" class="button is-light cancel">Cancel</button>
                            </div>
                        </x-slot:footer>
                    </x-ui.modal>
                    <form id="complete-post" method="POST" action="{{ route('posts.complete', $post) }}">
                        @csrf
                        <button type="submit" class="button is-success">Complete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="block mt-3">
        <div class="tags has-addons">
            <span class="tag">Progress</span>
            <span class="tag has-text-weight-bold
                {{ $post->time_spent > $post->time_estimated ? 'has-text-danger' : '' }}">
                {{ $post->progress }}%
            </span>
        </div>
        @if($post->project)
            <p>Belongs to project: {{ $post->project->title }}</p>
        @endif
        Here are some debug values (should be removed before production):
        <br>Created: {{ $post->created_at }}
        <br>Estimated: {{ $post->time_estimated }}
        <br>Spent: {{ $post->time_spent }}
        <br>Expect: {{ $post->expect_completed_at }}
        <br>
        <x-ui.date-tag title="Created">{{ $post->created_at }}</x-ui.date-tag>
        <x-ui.date-tag title="Updated">{{ $post->updated_at }}</x-ui.date-tag>
        @if($post->completed_at)
            <x-ui.date-tag title="Completed">{{ $post->completed_at }}</x-ui.date-tag>
        @endif
        <h2 class="subtitle is-6">
            {!! $post->description !!}
        </h2>
        <div class="block">
            <div class="columns">
                <div class="column">
                    <h1 class="title is-4">Comments</h1>
                </div>
                <div class="column">
                    <form method="POST" action="{{ route('posts.comments.store', $post) }}">
                        @csrf
                        <div class="field">
                            <label class="label">Add a Comment</label>
                            <div class="control">
                                <textarea class="textarea" name="content" required></textarea>
                            </div>
                        </div>
                        <div class="control">
                            <button type="submit" class="button is-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            @forelse($post->comments as $comment)
                <article class="media">
                    <div class="media-content">
                        <div class="content">
                            <p>{{ $comment->content }}</p>
                        </div>
                    </div>
                    <div class="media-right">
                    </div>
                </article>
            @empty
                There are no comments yet
            @endforelse
        </div>
    </div>
</x-layout.main>
