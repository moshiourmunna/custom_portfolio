@extends('layouts.site', [
  'metaTitle' => $page->meta_title,
  'metaDescription' => $page->meta_description,
  'ogImage' => $page->field('hero_image'),
])

@push('head')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'Organization',
  'name' => $site->site_name,
  'url' => rtrim($site->canonical_base ?: url('/'), '/').'/',
  'email' => $site->email,
  'telephone' => $site->phone,
  'description' => $site->meta_description,
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
  @php
    $stats = $page->blocksFor('stats');
    $products = $page->blocksFor('products');
    $insights = $page->blocksFor('insights');
    $why = $page->blocksFor('why_items');
    $steps = $page->blocksFor('steps');
    $bullets = $page->blocksFor('facility_bullets');
    $points = $page->blocksFor('quality_points');
    $markets = $page->blocksFor('markets');
    $strip = $page->blocksFor('gallery_strip');
    $figures = $page->blocksFor('facility_figures');
    $statIcons = ['stat-looms', 'stat-yarn', 'stat-globe', 'stat-people'];
    $productIcons = ['product-yarn', 'product-weave', 'product-finish'];
    $productHrefs = [
      'Yarn' => route('products.yarn'),
      'Woven Fabric' => route('products.woven'),
      'Finished Fabric' => route('products.finished'),
    ];
    $whyIcons = ['trust-shield', 'trust-mill', 'trust-clock', 'trust-export'];
    $stepIcons = ['step-fiber', 'step-spin', 'step-weave', 'step-dye', 'step-finish', 'step-dispatch'];
    $certIcons = ['cert-lab', 'cert-process', 'cert-doc', 'cert-shield'];
  @endphp

  @foreach($page->sectionOrder() as $sectionId)
    @includeIf('partials.home.'.$sectionId)
  @endforeach
@endsection
