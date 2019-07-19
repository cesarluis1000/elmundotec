<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
        <loc><?php echo Router::url('/',true); ?></loc>
        <lastmod><?php echo '2017-06-20'; ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <?php if (!empty($a_categoria)):?>
        <?php foreach ($a_categoria as $Categoria): ?>
        <url>
            <loc><?php echo Router::url(array('controller'=>'productos', 'action'=>'buscador','slug' =>  $Categoria['Categoria']['seo_url']),true); ?></loc>
            <lastmod><?php echo date('Y-m-d'); ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (!empty($a_marcas)):?>
        <?php foreach ($a_marcas as $marca_id => $marca):?>
        <url>
            <loc><?php echo Router::url(array('controller'=>'productos', 'action'=>'buscador','slug' =>  $marca['Marca']['seo_url']),true); ?></loc>
            <lastmod><?php echo date('Y-m-d'); ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
        <?php endforeach; ?>
    <?php endif; ?>
</urlset>