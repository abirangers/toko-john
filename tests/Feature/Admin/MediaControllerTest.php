<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\MediaCrudController;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Log;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaControllerTest extends AdminTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $controller = new MediaCrudController();
        $this->assertInstanceOf(HasMiddleware::class, $controller);
    }

    public function test_admin_media_access() {
        $admin = User::factory()->create();
        $admin->syncRoles('admin');
        $admin->givePermissionTo('find-all-media', 'delete-media', 'bulk-delete-media');

        $route = route("admin.media.index");
        $this->actingAs($admin)->get($route)->assertOk();

        $media = Media::create([
            'name' => 'example.jpg',
            'path' => 'example.jpg',
            'user_id' => $admin->id
        ]);

        Storage::put("public/images/products/example.jpg", "dummy content");

        $response = $this->actingAs($admin)->delete(route('admin.media.destroy', $media->id));
        $response->assertRedirect(route('admin.media.index'));
        $response->assertSessionHas('success', 'Media deleted successfully');
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        $this->assertFalse(Storage::exists("public/images/products/example.jpg"));

        $media2 = Media::create([
            'name' => 'example2.jpg',
            'path' => 'example2.jpg',
            'user_id' => $admin->id
        ]);

        Storage::put("public/images/products/example2.jpg", "dummy content");

        $response = $this->actingAs($admin)->delete(route('admin.media.bulkDestroy'), ['ids' => [$media->id, $media2->id]]);
        $response->assertRedirect(route('admin.media.index'));
        $response->assertSessionHas('success', 'Bulk media deleted successfully');
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        $this->assertDatabaseMissing('media', ['id' => $media2->id]);
        $this->assertFalse(Storage::exists("public/images/products/example.jpg"));
        $this->assertFalse(Storage::exists("public/images/products/example2.jpg"));
    }

    public function test_user_media_access()
    {
        $user = User::factory()->create();
        $user->syncRoles('user');

        $route = route("admin.media.index");
        $this->actingAs($user)->get($route)->assertStatus(403);

        $media = Media::create([
            'name' => 'example.jpg',
            'path' => 'example.jpg',
            'user_id' => $user->id
        ]);

        Storage::put("public/images/products/example.jpg", "dummy content");

        $response = $this->actingAs($user)->delete(route('admin.media.destroy', $media->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('media', ['id' => $media->id]);
        $this->assertTrue(Storage::exists("public/images/products/example.jpg"));

        $media2 = Media::create([
            'name' => 'example2.jpg',
            'path' => 'example2.jpg',
            'user_id' => $user->id
        ]);

        Storage::put("public/images/products/example2.jpg", "dummy content");

        $response = $this->actingAs($user)->delete(route('admin.media.bulkDestroy'), ['ids' => [$media->id, $media2->id]]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('media', ['id' => $media->id]);
        $this->assertDatabaseHas('media', ['id' => $media2->id]);
        $this->assertTrue(Storage::exists("public/images/products/example.jpg"));
        $this->assertTrue(Storage::exists("public/images/products/example2.jpg"));
    }

    public function test_guest_media_access()
    {
        $user = User::factory()->create();

        $route = route("admin.media.index");
        $this->get($route)->assertStatus(302)->assertRedirect(route('login'));

        $media = Media::create([
            'name' => 'example.jpg',
            'path' => 'example.jpg',
            'user_id' => $user->id
        ]);

        Storage::put("public/images/products/example.jpg", "dummy content");

        $response = $this->delete(route('admin.media.destroy', $media->id));
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseHas('media', ['id' => $media->id]);
        $this->assertTrue(Storage::exists("public/images/products/example.jpg"));

        $media2 = Media::create([
            'name' => 'example2.jpg',
            'path' => 'example2.jpg',
            'user_id' => $user->id
        ]);

        Storage::put("public/images/products/example2.jpg", "dummy content");

        $response = $this->delete(route('admin.media.bulkDestroy'), ['ids' => [$media->id, $media2->id]]);
        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertDatabaseHas('media', ['id' => $media->id]);
        $this->assertDatabaseHas('media', ['id' => $media2->id]);
        $this->assertTrue(Storage::exists("public/images/products/example.jpg"));
        $this->assertTrue(Storage::exists("public/images/products/example2.jpg"));
    }
}