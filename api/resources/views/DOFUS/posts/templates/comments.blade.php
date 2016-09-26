@foreach ($comments as $comment)
@include('posts.templates.comment')
@endforeach
<div class="ak-pagination text-center" id="pagination" data-lastpage="{{ $comments->lastPage() }}">
    <nav>
        {{  $comments->links() }}
    </nav>
</div>