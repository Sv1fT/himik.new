<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url>
        <loc>https://opt-himik.ru/</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://opt-himik.ru/register</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://opt-himik.ru/login</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://opt-himik.ru/tsb/</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://opt-himik.ru/company</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://opt-himik.ru/jobs</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://opt-himik.ru/blog</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://opt-himik.ru/region</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://opt-himik.ru/spros</loc>
        <lastmod>{{date('c',time())}}</lastmod>
        <priority>1.0</priority>
    </url>
    @foreach($blogs as $blog)
        <url>
            <loc>https://opt-himik.ru/blog/post/{{$blog->id}}</loc>
            <lastmod>{{date('c',time())}}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($companys as $company)
        <url>
            <loc>https://opt-himik.ru/blog/company/{{$company->id}}</loc>
            <lastmod>{{date('c',time())}}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($categorys as $category)
        <url>
            <loc>https://opt-himik.ru/tsb/{{$category->slug}}</loc>
            <lastmod>{{date('c',time())}}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($subcategorys as $subcategory)
        <url>
            <loc>https://opt-himik.ru/advert/{{$subcategory->slug}}</loc>
            <lastmod>{{date('c',time())}}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($adverts as $advert)
        <url>
            <loc>
                @if(isset($advert->citys->slug))
                    https://{{$advert->citys->slug}}.opt-himik.ru/{{$advert->slug}}
                @else
                    https://opt-himik.ru/{{$advert->slug}}
                @endif
            </loc>
            <lastmod>{{date('c',time())}}</lastmod>
            <priority>1.0</priority>
        </url>
    @endforeach
    @foreach($vacants as $vacant)
        <url>
            <loc>https://opt-himik.ru/vacant/{{$subcategory->slug}}</loc>
            <lastmod>{{date('c',time())}}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($resumes as $resume)
        <url>
            <loc>https://opt-himik.ru/resume/{{$resume->slug}}</loc>
            <lastmod>{{date('c',time())}}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($regions as $region)
        <url>
            <loc>https://opt-himik.ru/region/{{$region->id}}</loc>
            <lastmod>{{date('c',time())}}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>