<div class="ak-comments-row @if ($comment->author->isStaff()) ak-avatar-admin @endif">
    <div class="ak-avatar">
        <div class="ak-avatar-img">
            <img src="{{ URL::asset($comment->author->avatar) }}" alt="" border="0" /> </div>
        <div class="ak-avatar-tag">@if ($comment->author->isStaff()) Staff {{ config('dofus.title') }} @else Joueur @endif</div>
    </div>
    <div class="ak-comment">
        <div class="ak-user">
            <strong>{{ $comment->author->firstname }}</strong>
            <small class="ak-time">{{ date('d F Y à H:i', strtotime($comment->created_at)) }}</small>
        </div>
        <div class="ak-text-content">{{ $comment->text }}</div>
    </div>
</div>