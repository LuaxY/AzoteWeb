<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
    <channel>
        <title>{{ config('dofus.title') }} - {{ config('dofus.subtitle') }}</title>
        <link>{{ url('/') }}</link>
        <description>{{ config('dofus.title') }} Serveurs Dofus 2.10 & 2.35</description>
        @foreach ($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ URL::route('posts.show', [$post->id, $post->slug]) }}</link>
            <description>{{ $post->preview }}</description>
        </item>
        @endforeach
    </channel>
</rss>
