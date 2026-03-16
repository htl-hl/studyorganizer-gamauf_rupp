<?php

use app\models\Course;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\CouseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var bool $isAdmin */

$this->title = Yii::t('app', 'Courses');
$this->params['breadcrumbs'][] = $this->title;

$courses = $dataProvider->getModels();
?>


<div class="course-page">

    <div class="course-header">
        <div class="course-header__titles">
            <h1>Subjects</h1>
            <p>A list of all subjects.</p>
        </div>
        <?php if ($isAdmin): ?>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#courseModal" onclick="openCreateModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                     stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                New Subject
            </button>
        <?php endif; ?>
    </div>

    <?php Pjax::begin(['id' => 'courses-pjax']); ?>
    <div class="course-grid">

        <?php if ($isAdmin): ?>
            <div class="card-add" data-bs-toggle="modal" data-bs-target="#courseModal" onclick="openCreateModal()">
                <div class="card-add__inner">
                    <div class="card-add__icon">+</div>
                    <span class="card-add__label">Add subject</span>
                </div>
            </div>
        <?php endif; ?>

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
                <div class="course-card" style="cursor:pointer"
                     onclick="showTeachers(<?= $course->id ?>, '<?= Html::encode(addslashes($course->course_name)) ?>', <?= htmlspecialchars(json_encode(
                             array_map(fn($t) => $t->teacher_name, $course->teachers ?? []), ENT_QUOTES
                     )) ?>)">

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
                            <?php if ($isAdmin): ?>
                                <a href="#" title="Edit"
                                   onclick="openEditModal(<?= $course->id ?>, <?= htmlspecialchars(json_encode([
                                           'course_name' => $course->course_name,
                                   ]), ENT_QUOTES) ?>); return false;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>

                                <?= Html::beginForm(Url::to(['course/delete', 'id' => $course->id]), 'post', ['style' => 'display:inline', 'onclick' => 'event.stopPropagation()']) ?>
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
                            <?php endif; ?>
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

<!-- Teacher List Popup -->
<div class="modal fade" id="teacherListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teacherListModalLabel">Teachers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="teacherListBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Close</button>
            </div>
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
        // stop click from bubbling to card
        event.stopPropagation();
        new bootstrap.Modal(document.getElementById('courseModal')).show();
    }

    function openEditModal(id, data) {
        event.stopPropagation();
        document.getElementById('courseModalLabel').textContent = 'Edit Subject';
        document.getElementById('course-form').action = urlCourseUpdate(id);
        document.getElementById('modal-course-name').value = data.course_name ?? '';
        new bootstrap.Modal(document.getElementById('courseModal')).show();
    }

    function showTeachers(courseId, courseName, teachers) {
        document.getElementById('teacherListModalLabel').textContent = courseName + ' – Teachers';
        const body = document.getElementById('teacherListBody');
        if (!teachers || teachers.length === 0) {
            body.innerHTML = '<p class="empty-teacher-msg">No teachers assigned yet.</p>';
        } else {
            body.innerHTML = teachers.map(name => `
                <div class="teacher-list-item">
                    <div class="teacher-list-avatar">${name.charAt(0).toUpperCase()}</div>
                    <span class="teacher-list-name">${name}</span>
                </div>
            `).join('');
        }
        new bootstrap.Modal(document.getElementById('teacherListModal')).show();
    }
</script>