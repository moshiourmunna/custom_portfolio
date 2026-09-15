@if($page->sectionActive('insights'))
  <section class="home-insight">
    <div class="container">
      <div class="home-insight__grid">
        @foreach($insights as $item)
          <article class="home-insight__col reveal @if($item->title === 'Latest News') home-insight__col--news @endif">
            <div class="home-insight__content">
              @if(mill_filled($item->title))<h3>{{ $item->title }}</h3>@endif
              @if($item->title === 'Latest News')
                <div class="news-teaser">
                  @foreach($posts as $post)
                    <div class="news-teaser__item">
                      <div class="news-teaser__date">{{ $post->published_on?->format('M j, Y') }}</div>
                      <a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a>
                    </div>
                  @endforeach
                </div>
              @elseif(mill_filled($item->text))
                <p>{{ $item->text }}</p>
              @endif
              @if(mill_filled($item->link_label))
                <a href="{{ $item->href }}" class="link-more link-more--serif">{{ $item->link_label }} →</a>
              @endif
            </div>
            <div class="home-insight__visual @if($item->title === 'Sustainability') home-insight__visual--leaf @endif" aria-hidden="true">
              @if($item->title === 'Sustainability')
                @include('partials.icon', ['icon' => 'leaf'])
              @elseif(mill_url($item->image))
                <img src="{{ mill_url($item->image) }}" alt="">
              @endif
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif
