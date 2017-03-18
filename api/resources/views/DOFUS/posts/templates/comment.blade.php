<div class="ak-comments-row @if ($comment->author->isStaff()) ak-avatar-admin @endif">
    <div class="ak-avatar">
        <div class="ak-avatar-img">
            <img src="{{ URL::asset($comment->author->avatar) }}" alt="" border="0" /> </div>
        <div class="ak-avatar-tag">{{$comment->author->role->label}}</div>
    </div>
    <div class="ak-comment">
        <div class="ak-user">
            <strong>{{ $comment->author->firstname }}</strong>
            <small class="ak-time">{{ date('d F Y à H:i', strtotime($comment->created_at)) }}</small>
            <span class="actions pull-right">
                @if(!Auth::guest() && Auth::user()->can('delete-comments'))
                    {!! Form::open(['route' => ['posts.comment.destroy', $post->id, $post->slug, $comment->id], 'class' => 'form-inline']) !!}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                    {!! Form::close() !!}
                @endif
            </span>
        </div>
        <div class="ak-text-content">{{ $comment->text }}</div>
    </div>
</div>