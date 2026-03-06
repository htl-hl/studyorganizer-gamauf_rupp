<?php

use app\models\Teacher;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\TeacherSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\Course[] $courses */

$this->title = Yii::t('app', 'Teachers');
$this->params['breadcrumbs'][] = $this->title;

$teachers = $dataProvider->getModels();
?>

<style>
    /* ── Page Layout ─────────────────────────────────────────── */
    .teacher-page { padding: 2rem 0 4rem; }

    .teacher-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .teacher-header__titles h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 .2rem;
        letter-spacing: -.5px;
    }

    .teacher-header__titles p {
        color: #64748b;
        margin: 0;
        font-size: .95rem;
    }

    /* ── Add Button ──────────────────────────────────────────── */
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

    /* ── Grid ────────────────────────────────────────────────── */
    .teacher-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.1rem;
    }

    /* ── Add Card ────────────────────────────────────────────── */
    .card-add {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        background: transparent;
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

    /* ── Teacher Card ────────────────────────────────────────── */
    .teacher-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.1rem 1.2rem;
        display: flex;
        flex-direction: column;
        gap: .55rem;
        transition: box-shadow .2s, transform .15s;
    }
    .teacher-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.08); transform: translateY(-2px); }
    .teacher-card--inactive { opacity: .55; }

    .teacher-card__top {
        display: flex;
        align-items: center;
        gap: .85rem;
    }

    .teacher-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: #475569;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .teacher-card__name {
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }

    .teacher-card__meta {
        font-size: .78rem;
        color: #64748b;
        display: flex;
        flex-direction: column;
        gap: .18rem;
    }
    .teacher-card__meta span {
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .teacher-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: .3rem;
    }

    .badge-active {
        font-size: .7rem; padding: .25rem .6rem; border-radius: 20px;
        font-weight: 600; background: #dcfce7; color: #16a34a;
    }
    .badge-inactive {
        font-size: .7rem; padding: .25rem .6rem; border-radius: 20px;
        font-weight: 600; background: #f1f5f9; color: #64748b;
    }

    .teacher-card__actions { display: flex; gap: .35rem; }
    .teacher-card__actions a,
    .teacher-card__actions button {
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
    .teacher-card__actions a:hover,
    .teacher-card__actions button:hover { background: #e2e8f0; color: #0f172a; }
    .teacher-card__actions .btn-delete:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

    /* ── Empty State ─────────────────────────────────────────── */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1rem;
        color: #94a3b8;
    }
    .empty-state__icon { font-size: 3rem; margin-bottom: .75rem; }

    /* ── Modal ───────────────────────────────────────────────── */
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

<div class="teacher-page">

    <!-- Header -->
    <div class="teacher-header">
        <div class="teacher-header__titles">
            <h1>Teachers</h1>
            <p>A list of every teacher.</p>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#teacherModal" onclick="openCreateModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            New Teacher
        </button>
    </div>

    <!-- Grid -->
    <?php Pjax::begin(['id' => 'teachers-pjax']); ?>
    <div class="teacher-grid">

        <!-- Add tile -->
        <div class="card-add" data-bs-toggle="modal" data-bs-target="#teacherModal" onclick="openCreateModal()">
            <div class="card-add__inner">
                <div class="card-add__icon">+</div>
                <span class="card-add__label">Add teacher</span>
            </div>
        </div>

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
                // Show multiple courses via relation
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