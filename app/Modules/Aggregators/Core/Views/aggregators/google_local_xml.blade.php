<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
<channel>
    <title>PdParis - Online Store</title>
    <link>http://pdparis-wish.com.ua</link>
    <description>Познакомьтесь с мировыми парфюмерными шедеврами. Единая цена зафиксирована!</description>

    @foreach($products as $product)
        <item>
            <g:id>{{$product->id }}</g:id>
            @if($product->category_id == 1 && $product->volume == 50)
                <g:title>{{ $product->name .' 50 мл' }}</g:title>
            @elseif($product->category_id == 1 && $product->volume == 100)
                <g:title>{{ $product->name .' 100 мл' }}</g:title>
            @elseif($product->category_id == 2 && $product->volume == 50)
                <g:title>{{ $product->name .' 50 мл'}}</g:title>
            @elseif($product->category_id == 2 && $product->volume == 100)
                <g:title>{{ $product->name .' 100 мл'}}</g:title>
            @endif
            <g:description>{{ $product->description }}</g:description>
            <g:link>http://pdparis-wish.com.ua</g:link>
            <g:image>{{ url('/') . $product->img2 }}</g:image>
            <g:condition>new</g:condition>
            <g:availability>in stock</g:availability>
            <g:price>{{ $product->price_ua }} UAH</g:price>
            <g:brand>PdParis</g:brand>
            {{--<g:shipping></g:shipping>
            <g:gtin></g:gtin>
            <g:mpn></g:mpn>
            <g:google_product_category></g:google_product_category>
            <g:product_type></g:product_type>--}}
        </item>
    @endforeach

</channel>
</rss>
