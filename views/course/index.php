<?php

use app\models\Course;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\CouseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Courses');
$this->params['breadcrumbs'][] = $this->title;

$courses = $dataProvider->getModels();
?>

<style>
    .course-page { padding: 2rem 0 4rem; }

    .course-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .course-header__titles h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 .2rem;
        letter-spacing: -.5px;
    }
    .course-header__titles p { color: #64748b; margin: 0; font-size: .95rem; }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        background: #0f172a;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .6rem 1.2rem;
        font-size: .9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .15s;
        text-decoration: none;
    }
    .btn-add:hover { background: #1e293b; color: #fff; transform: translateY(-1px); }

    .course-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.1rem;
    }

    .card-add {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .card-add:hover { border-color: #94a3b8; background: #f8fafc; }
    .card-add__inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .5rem;
        color: #94a3b8;
    }
    .card-add__icon {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .card-add__label { font-size: .85rem; font-weight: 500; }

    .course-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.1rem 1.2rem;
        display: flex;
        flex-direction: column;
        gap: .55rem;
        transition: box-shadow .2s, transform .15s;
    }
    .course-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.08); transform: translateY(-2px); }

    .course-card__icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .course-card__name {
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
        margin: 0;
    }
    .course-card__meta {
        font-size: .78rem;
        color: #64748b;
        display: flex;
        flex-direction: column;
        gap: .18rem;
    }
    .course-card__meta span { display: flex; align-items: center; gap: .3rem; }

    .course-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: .3rem;
    }

    .badge-count {
        font-size: .7rem; padding: .25rem .6rem; border-radius: 20px;
        font-weight: 600; background: #eff6ff; color: #3b82f6;
    }

    .course-card__actions { display: flex; gap: .35rem; }
    .course-card__actions a,
    .course-card__actions button {
        width: 30px; height: 30px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .15s;
        color: #475569;
        text-decoration: none;
        padding: 0;
    }
    .course-card__actions a:hover,
    .course-card__actions button:hover { background: #e2e8f0; color: #0f172a; }
    .course-card__actions .btn-delete:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1rem;
        color: #94a3b8;
    }
    .empty-state__icon { font-size: 3rem; margin-bottom: .75rem; }

    .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.15); }
    .modal-header { border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem .9rem; }
    .modal-title { font-weight: 700; font-size: 1.05rem; color: #0f172a; }
    .modal-body { padding: 1.25rem 1.5rem; }
    .modal-footer { border-top: 1px solid #f1f5f9; padding: .9rem 1.5rem; }

    .form-label { font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .3rem; }
    .form-control, .form-select {
        border-radius: 9px; border: 1px solid #e2e8f0;
        font-size: .9rem; padding: .55rem .8rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0f172a;
        box-shadow: 0 0 0 3px rgba(15,23,42,.08);
    }

    .btn-save {
        background: #0f172a; color: #fff; border: none;
        border-radius: 9px; padding: .6rem 1.4rem;
        font-weight: 600; font-size: .9rem; transition: background .2s;
    }
    .btn-save:hover { background: #1e293b; color: #fff; }

    .btn-cancel-modal {
        background: transparent; border: 1px solid #e2e8f0;
        border-radius: 9px; padding: .6rem 1.1rem;
        font-size: .9rem; color: #64748b; transition: background .15s;
    }
    .btn-cancel-modal:hover { background: #f1f5f9; }
</style>

<div class="course-page">

    <div class="course-header">
        <div class="course-header__titles">
            <h1>Subjects</h1>
            <p>A list of all subjects.</p>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#courseModal" onclick="openCreateModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            New Subject
        </button>
    </div>

    <?php Pjax::begin(['id' => 'courses-pjax']); ?>
    <div class="course-grid">

        <div class="card-add" data-bs-toggle="modal" data-bs-target="#courseModal" onclick="openCreateModal()">
            <div class="card-add__inner">
                <div class="card-add__icon">+</div>
                <span class="card-add__label">Add subject</span>
            </div>
        </div>

        <?php if (empty($courses)): ?>
            <div class="empty-state">
                <div class="empty-state__icon">📚</div>
                <p>No subjects yet. Create your first one!</p>
            </div>
        <?php else: ?>
            <?php foreach ($courses as $course): ?>
                <?php
                $teacherCount = count($course->teachers ?? []);
                ?>
                <div class="course-card">

                    <div class="course-card__icon">📖</div>
                    <p class="course-card__name"><?= Html::encode($course->course_name) ?></p>

                    <div class="course-card__meta">
            <span>
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
              Taught by <?= $teacherCount ?> teacher<?= $teacherCount !== 1 ? 's' : '' ?>
            </span>
                    </div>

                    <div class="course-card__footer">
                        <span class="badge-count"><?= $teacherCount ?> teacher<?= $teacherCount !== 1 ? 's' : '' ?></span>
                        <div class="course-card__actions">

                            <a href="#" title="Edit"
                               data-bs-toggle="modal"
                               data-bs-target="#courseModal"
                               onclick="openEditModal(<?= $course->id ?>, <?= htmlspecialchars(json_encode([
                                       'course_name' => $course->course_name,
                               ]), ENT_QUOTES) ?>); return false;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>

                            <?= Html::beginForm(Url::to(['course/delete', 'id' => $course->id]), 'post', ['style' => 'display:inline']) ?>
                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                            <button type="submit" class="btn-delete" title="Delete"
                                    onclick="return confirm('Delete this subject?')">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14H6L5 6"/>
                                    <path d="M10 11v6"/><path d="M14 11v6"/>
                                    <path d="M9 6V4h6v2"/>
                                </svg>
                            </button>
                            <?= Html::endForm() ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
    <?php Pjax::end(); ?>
</div>

<!-- Modal -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="courseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseModalLabel">New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="course-form" method="post">
                <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                <div class="modal-body d-flex flex-column gap-3">
                    <div>
                        <label class="form-label" for="modal-course-name">Name</label>
                        <input type="text" id="modal-course-name" name="Course[course_name]"
                               class="form-control" placeholder="e.g. Mathematics" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const urlCourseCreate = '<?= Url::to(['course/create']) ?>';
    const urlCourseUpdate = (id) =>
        '<?= Url::to(['course/update', 'id' => '__ID__']) ?>'.replace('__ID__', id);

    function openCreateModal() {
        document.getElementById('courseModalLabel').textContent = 'New Subject';
        document.getElementById('course-form').action = urlCourseCreate;
        document.getElementById('modal-course-name').value = '';
    }

    function openEditModal(id, data) {
        document.getElementById('courseModalLabel').textContent = 'Edit Subject';
        document.getElementById('course-form').action = urlCourseUpdate(id);
        document.getElementById('modal-course-name').value = data.course_name ?? '';
    }
</script>