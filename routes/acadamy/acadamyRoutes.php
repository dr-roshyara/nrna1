 <?php 
use App\Http\Controllers\StudentController;

  Route::get('/student/create', [StudentController::class, 'create'])->name('student.create');
  Route::post('/student/store', [StudentController::class, 'store'])->name('student.store');
  Route::get('/student/show', [StudentController::class, 'show'])->name('student.show'); 
