<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AffiliateWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_loads(): void
    {
        $this->get('/')->assertOk()->assertSee('Gen-Z Finds');
    }

    public function test_catalog_and_product_detail_load(): void
    {
        $product = Product::query()->firstOrFail();

        $this->get(route('products.index'))->assertOk();
        $this->get(route('products.show', $product->slug))->assertOk()->assertSee($product->name);
    }

    public function test_affiliate_click_is_recorded_and_redirects(): void
    {
        $product = Product::query()->firstOrFail();

        $this->get(route('affiliate.go', $product))
            ->assertRedirect($product->affiliate_url);

        $this->assertDatabaseCount('affiliate_clicks', 1);
        $this->assertSame(1, $product->fresh()->clicks);
    }

    public function test_guest_cannot_open_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_login_and_create_product_with_image(): void
    {
        $admin = User::query()->where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Produk Uji Coba',
                'slug' => 'produk-uji-coba',
                'affiliate_url' => 'https://shopee.co.id/product/123',
                'price' => 120000,
                'rating' => 4.8,
                'status' => Product::STATUS_ACTIVE,
                'images' => [UploadedFile::fake()->image('foto.jpg', 600, 600)],
            ])
            ->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('slug', 'produk-uji-coba')->firstOrFail();
        $image = $product->images()->firstOrFail();

        $this->assertFileExists(Storage::disk('public')->path($image->image_path));
        $this->assertSame(Storage::disk('public')->url($image->image_path), $image->url());
        $this->assertSame(Storage::disk('public')->url($image->image_path), $product->imageUrl());

        Storage::disk('public')->delete($image->image_path);
    }

    public function test_all_admin_pages_render(): void
    {
        $admin = User::query()->where('is_admin', true)->firstOrFail();
        $product = Product::query()->firstOrFail();
        $category = \App\Models\Category::query()->firstOrFail();
        $review = \App\Models\Review::query()->create([
            'product_id' => $product->id,
            'name' => 'Tester',
            'rating' => 5,
            'comment' => 'Mantap',
        ]);

        $this->actingAs($admin)->get(route('admin.login'))->assertRedirect(route('admin.dashboard'));

        $urls = [
            route('admin.dashboard'),
            route('admin.products.index'),
            route('admin.products.create'),
            route('admin.products.edit', $product),
            route('admin.categories.index'),
            route('admin.categories.create'),
            route('admin.categories.edit', $category),
            route('admin.reviews.index'),
            route('admin.statistics.index'),
            route('admin.settings.edit'),
        ];

        foreach ($urls as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        $this->actingAs($admin)->patch(route('admin.reviews.toggle', $review))->assertRedirect();
        $this->assertTrue($review->fresh()->is_approved);
    }
}
