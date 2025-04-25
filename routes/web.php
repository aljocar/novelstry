<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NovelController;
use App\Http\Controllers\Admin\NovelController as AdminNovelController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserMetadataController;
use App\Services\ImgurService;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/test-imgur', function() {
    $testImage = base64_encode(file_get_contents('https://picsum.photos/200/300'));
    $imgur = app(ImgurService::class);
    $url = $imgur->uploadBase64Image($testImage);
    return $url ?: "Error al subir imagen";
});

// Novelas (protegidas)
Route::middleware('auth')->group(function () {

    //Crear
    Route::get('/novels/create', [NovelController::class, 'create'])
        ->name('novels.create');
    Route::post('/novels', [NovelController::class, 'store'])
        ->name('novels.store');

    //Editar
    Route::get('/novels/{novel}/edit', [NovelController::class, 'edit'])
        ->name('novels.edit');
    Route::put('/novels/{novel}', [NovelController::class, 'update'])
        ->name('novels.update');

    //Eliminar
    Route::get('/novels/{novel}/delete', [NovelController::class, 'delete'])
        ->name('novels.delete');
    Route::delete('/novels/{novel}', [NovelController::class, 'destroy'])
        ->name('novels.destroy');



    //Comentarios Novelas
    Route::post('/comments/{commentableType}/{commentableId}', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');



    //Capitulos
    //Crear
    Route::get('/novels/{novel}/chapter/create', [ChapterController::class, 'create'])
        ->name('chapters.create');
    Route::post('/novels/{novel}', [ChapterController::class, 'store'])
        ->name('chapters.store');

    //Editar
    Route::get('/novels/{novel}/{chapter}/edit', [ChapterController::class, 'edit'])
        ->name('chapters.edit');
    Route::put('/novels/{novel}/{chapter}', [ChapterController::class, 'update'])
        ->name('chapters.update');

    //Eliminar
    Route::get('/novels/{novel}/{chapter}/delete', [ChapterController::class, 'delete'])
        ->name('chapters.delete');
    Route::delete('/novels/{novel}/{chapter}', [ChapterController::class, 'destroy'])
        ->name('chapters.destroy');



    //Favoritos
    Route::get('/my-favorites', [FavoriteController::class, 'myFavorites'])
        ->name('favorites.index');

    Route::post('/novels/{novel}/favorite', [FavoriteController::class, 'store'])
        ->name('novels.favorite');
    Route::get('/novels/{novel}/unfavorite', [FavoriteController::class, 'destroy'])
        ->name('novels.unfavorite');


    Route::middleware(['auth', 'account.owner'])->group(function () {
        //Perfil
        //Configurar
        Route::get('/profile/{username}/config', [ProfileController::class, 'config'])
            ->name('profiles.config');

        // Editar
        Route::get('/profile/{username}/edit', [ProfileController::class, 'edit'])
            ->name('profiles.edit');
        Route::put('/profile/{username}', [ProfileController::class, 'update'])
            ->name('profiles.update');

        //Eliminar
        Route::get('/profile/{username}/delete', [ProfileController::class, 'delete'])
            ->name('profiles.delete');
        Route::delete('/profile/{username}', [ProfileController::class, 'destroy'])
            ->name('profiles.destroy');

        // Editar Imagen de perfil
        Route::get('/profile/{username}/image/edit', [ProfileController::class, 'imageEdit'])
            ->name('profiles.image.edit');
        Route::put('/profile/{username}/image', [ProfileController::class, 'imageUpdate'])
            ->name('profiles.image.update');



        //Informacion Adicional (UserMetadata)
        //Crear
        Route::get('/profile/config/{username}/create', [UserMetadataController::class, 'create'])
            ->name('profiles.metadata.create');
        Route::post('/profile/config/{username}', [UserMetadataController::class, 'store'])
            ->name('profiles.metadata.store');

        // Editar
        Route::get('/profile/config/{username}/edit', [UserMetadataController::class, 'edit'])
            ->name('profiles.metadata.edit');
        Route::put('/profile/config/{username}', [UserMetadataController::class, 'update'])
            ->name('profiles.metadata.update');
    });
});

//Usuario Administrador
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/admin/novels', [AdminNovelController::class, 'indexNovel'])
        ->name('admin.novel.index');

    Route::get('/admin/chapters', [AdminNovelController::class, 'indexChapter'])
        ->name('admin.chapter.index');

    Route::get('/admin/statistics', [StatisticsController::class, 'index'])
        ->name('admin.statistics.index');
    Route::post('/generate-pdf', [StatisticsController::class, 'generatePdf'])
        ->name('generate.pdf');



    //Comentarios Novelas
    Route::get('/admin/comments', [AdminCommentController::class, 'index'])
        ->name('admin.comments.index');

    Route::post('/admin/comments', [AdminCommentController::class, 'store'])
        ->name('admin.comments.store');

    Route::delete('/admin/comments/{comment}', [AdminCommentController::class, 'destroy'])
        ->name('admin.comments.destroy');

    Route::put('/admin/comments/{comment}', [AdminCommentController::class, 'update'])
        ->name('admin.comments.update');



    //Usuarios
    Route::get('/admin/users', [AdminController::class, 'index'])
        ->name('admin.users.index');
    //Crear
    Route::get('/admin/users/create', [AdminController::class, 'create'])
        ->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'store'])
        ->name('admin.users.store');
    //Editar
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])
        ->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])
        ->name('admin.users.update');
    //Eliminar
    Route::get('/admin/users/{user}/delete', [AdminController::class, 'delete'])
        ->name('admin.users.delete');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])
        ->name('admin.users.destroy');



    //Categorias
    Route::get('/admin/categories', [CategoryController::class, 'index'])
        ->name('admin.categories.index');
    //Crear
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])
        ->name('admin.categories.create');
    Route::post('/admin/categories', [CategoryController::class, 'store'])
        ->name('admin.categories.store');

    //Editar
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])
        ->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])
        ->name('admin.categories.update');

    //Eliminar
    Route::get('/admin/categories/{category}/delete', [CategoryController::class, 'delete'])
        ->name('admin.categories.delete');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('admin.categories.destroy');
});

//Novelas
Route::get('/novels', [NovelController::class, 'index'])
    ->name('novels.index');

//Mostrar
Route::get('/novels/{novel}', [NovelController::class, 'show'])
    ->name('novels.show');


//Comentarios
Route::get('/novels/{novel}/comments', [CommentController::class, 'index'])
    ->name('novels.comments.index');


//Capitulo
Route::get('/novels/{novel}/chapters', [ChapterController::class, 'index'])
    ->name('chapters.index');

Route::get('/novels/{novel}/{chapter}', [ChapterController::class, 'show'])
    ->name('chapters.show');



//Perfiles
Route::get('/profiles', [ProfileController::class, 'index'])
    ->name('profiles.index');
//Perfil
Route::get('/profile/{username}', [ProfileController::class, 'show'])
    ->name('profiles.show');


// Rutas de autenticación
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});
