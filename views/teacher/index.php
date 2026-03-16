<?php

use app\models\Teacher;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\TeacherSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Course[] $courses */
/** @var bool $isAdmin */

$this->title = Yii::t('app', 'Teachers');
$this->params['breadcrumbs'][] = $this->title;

$teachers = $dataProvider->getModels();
?>


<div class="teacher-page">

    <!-- Header -->
    <div class="teacher-header">
        <div class="teacher-header__titles">
            <h1>Teachers</h1>
            <p>A list of every teacher.</p>
        </div>
        <?php if ($isAdmin): ?>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#teacherModal" onclick="openCreateModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                     stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                New Teacher
            </button>
        <?php endif; ?>
    </div>

    <!-- Grid -->
    <?php Pjax::begin(['id' => 'teachers-pjax']); ?>
    <div class="teacher-grid">

        <?php if ($isAdmin): ?>
            <!-- Add tile -->
            <div class="card-add" data-bs-toggle="modal" data-bs-target="#teacherModal" onclick="openCreateModal()">
                <div class="card-add__inner">
                    <div class="card-add__icon">+</div>
                    <span class="card-add__label">Add teacher</span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($teachers)): ?>
            <div class="empty-state">
                <div class="empty-state__icon">👨‍🏫</div>
                <p>No teachers yet. Add your first one!</p>
            </div>
        <?php else: ?>
            <?php foreach ($teachers as $teacher): ?>
                <?php
                $isActive  = (bool)($teacher->is_active ?? 1);
                $initials  = strtoupper(substr($teacher->teacher_name, 0, 1));
                $courseNames = [];
                if (!empty($teacher->courses)) {
                    foreach ($teacher->courses as $c) {
                        $courseNames[] = $c->course_name;
                    }
                }
                ?>
                <div class="teacher-card <?= $isActive ? '' : 'teacher-card--inactive' ?>">

                    <div class="teacher-card__top">
                        <div class="teacher-avatar"><?= $initials ?></div>
                        <p class="teacher-card__name"><?= Html::encode($teacher->teacher_name) ?></p>
                    </div>

                    <div class="teacher-card__meta">
                        <?php if (!empty($courseNames)): ?>
                            <span>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                  <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                <?= Html::encode(implode(', ', $courseNames)) ?>
              </span>
                        <?php endif; ?>
                    </div>

                    <div class="teacher-card__footer">
            <span class="<?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
              <?= $isActive ? '✓ Active' : 'Inactive' ?>
            </span>
                        <div class="teacher-card__actions">
                            <?php if ($isAdmin): ?>
                                <!-- Edit -->
                                <a href="#" title="Edit"
                                   data-bs-toggle="modal"
                                   data-bs-target="#teacherModal"
                                   onclick="openEditModal(<?= $teacher->id ?>, <?= htmlspecialchars(json_encode([
                                           'teacher_name' => $teacher->teacher_name,
                                           'course_id'    => $teacher->course_id,
                                           'is_active'    => $teacher->is_active,
                                   ]), ENT_QUOTES) ?>); return false;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <?= Html::beginForm(Url::to(['teacher/delete', 'id' => $teacher->id]), 'post', ['style' => 'display:inline']) ?>
                                <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                                <button type="submit" class="btn-delete" title="Delete"
                                        onclick="return confirm('Delete this teacher?')">
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

<!-- ── Modal: Create / Edit Teacher ──────────────────────────── -->
<div class="modal fade" id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="teacherModalLabel">New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="teacher-form" method="post">
                <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>

                <div class="modal-body d-flex flex-column gap-3">

                    <!-- Name -->
                    <div>
                        <label class="form-label" for="modal-teacher-name">Name</label>
                        <input type="text" id="modal-teacher-name" name="Teacher[teacher_name]"
                               class="form-control" placeholder="e.g. Prof. Müller" required>
                    </div>

                    <!-- Course -->
                    <div>
                        <label class="form-label" for="modal-teacher-course">Subject</label>
                        <select id="modal-teacher-course" name="Teacher[course_id]" class="form-select" required>
                            <option value="">— Select subject —</option>
                            <?php if (!empty($courses ?? [])): ?>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= Html::encode($course->id) ?>"><?= Html::encode($course->course_name) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Active toggle -->
                    <div class="form-check form-switch">
                        <input type="hidden" name="Teacher[is_active]" value="0">
                        <input class="form-check-input" type="checkbox" id="modal-teacher-active"
                               name="Teacher[is_active]" value="1" checked>
                        <label class="form-check-label" for="modal-teacher-active"
                               style="font-size:.85rem;color:#374151;">Active</label>
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
    const urlTeacherCreate = '<?= Url::to(['teacher/create']) ?>';
    const urlTeacherUpdate = (id) =>
        '<?= Url::to(['teacher/update', 'id' => '__ID__']) ?>'.replace('__ID__', id);

    function openCreateModal() {
        document.getElementById('teacherModalLabel').textContent = 'New Teacher';
        document.getElementById('teacher-form').action = urlTeacherCreate;
        document.getElementById('modal-teacher-name').value = '';
        document.getElementById('modal-teacher-course').value = '';
        document.getElementById('modal-teacher-active').checked = true;
    }

    function openEditModal(id, data) {
        document.getElementById('teacherModalLabel').textContent = 'Edit Teacher';
        document.getElementById('teacher-form').action = urlTeacherUpdate(id);
        document.getElementById('modal-teacher-name').value   = data.teacher_name ?? '';
        document.getElementById('modal-teacher-course').value = data.course_id    ?? '';
        document.getElementById('modal-teacher-active').checked = !!parseInt(data.is_active ?? 1);
    }
</script>