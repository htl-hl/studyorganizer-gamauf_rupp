<?php

use app\models\Assignment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\AssignmentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;

$assignments = $dataProvider->getModels();
?>

<style>
    /* ── Page Layout ─────────────────────────────────────────── */
    .asgn-page { padding: 2rem 0 4rem; }

    .asgn-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .asgn-header__titles h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 .2rem;
        letter-spacing: -.5px;
    }

    .asgn-header__titles p {
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
    .asgn-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 1.1rem;
    }

    /* ── Add Card (big + tile) ───────────────────────────────── */
    .card-add {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        min-height: 150px;
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
        line-height: 1;
    }
    .card-add__label { font-size: .85rem; font-weight: 500; }

    /* ── Assignment Card ─────────────────────────────────────── */
    .asgn-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.1rem 1.2rem;
        display: flex;
        flex-direction: column;
        gap: .55rem;
        transition: box-shadow .2s, transform .15s;
    }
    .asgn-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.08); transform: translateY(-2px); }
    .asgn-card--done { opacity: .6; }
    .asgn-card--done .asgn-card__title { text-decoration: line-through; color: #94a3b8; }

    .asgn-card__title {
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
        margin: 0;
        line-height: 1.35;
        padding-right: 1.5rem;
    }

    .asgn-card__meta {
        font-size: .78rem;
        color: #64748b;
        display: flex;
        flex-direction: column;
        gap: .18rem;
    }
    .asgn-card__meta span { display: flex; align-items: center; gap: .3rem; }

    .asgn-card__due { font-size: .78rem; font-weight: 600; color: #ef4444; }
    .asgn-card__due--ok { color: #22c55e; }

    .asgn-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: .3rem;
    }

    .badge-done {
        font-size: .7rem; padding: .25rem .6rem; border-radius: 20px;
        font-weight: 600; background: #dcfce7; color: #16a34a;
    }
    .badge-pending {
        font-size: .7rem; padding: .25rem .6rem; border-radius: 20px;
        font-weight: 600; background: #fef9c3; color: #ca8a04;
    }

    .asgn-card__actions { display: flex; gap: .35rem; }
    .asgn-card__actions a,
    .asgn-card__actions button {
        width: 30px; height: 30px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .15s;
        color: #475569;
        text-decoration: none;
        font-size: .8rem;
        padding: 0;
    }
    .asgn-card__actions a:hover,
    .asgn-card__actions button:hover { background: #e2e8f0; color: #0f172a; }
    .asgn-card__actions .btn-delete:hover { background: #fee2e2; color: #ef4444; border-color: #fecaca; }

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

<div class="asgn-page">

    <!-- Header -->
    <div class="asgn-header">
        <div class="asgn-header__titles">
            <h1>Assignments</h1>
            <p>Chase your latest tasks.</p>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#assignmentModal" onclick="openCreateModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            New Assignment
        </button>
    </div>

    <!-- Grid -->
    <?php Pjax::begin(['id' => 'assignments-pjax']); ?>
    <div class="asgn-grid">

        <!-- Add tile -->
        <div class="card-add" data-bs-toggle="modal" data-bs-target="#assignmentModal" onclick="openCreateModal()">
            <div class="card-add__inner">
                <div class="card-add__icon">+</div>
                <span class="card-add__label">Add assignment</span>
            </div>
        </div>

        <?php if (empty($assignments)): ?>
            <div class="empty-state">
                <div class="empty-state__icon">📋</div>
                <p>No assignments yet. Create your first one!</p>
            </div>
        <?php else: ?>
            <?php foreach ($assignments as $assignment): ?>
                <?php
                $isDone    = (bool)$assignment->is_done;
                $dueDate   = $assignment->due_date ? date('d.m.Y', strtotime($assignment->due_date)) : null;
                $isOverdue = $assignment->due_date && strtotime($assignment->due_date) < time() && !$isDone;
                ?>
                <div class="asgn-card <?= $isDone ? 'asgn-card--done' : '' ?>">

                    <p class="asgn-card__title"><?= Html::encode($assignment->title) ?></p>

                    <div class="asgn-card__meta">
                        <?php if (!empty($assignment->course_id)): ?>
                            <span>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                  <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                Subject: <?= Html::encode($assignment->course->course_name ?? $assignment->course_id) ?>
              </span>
                        <?php endif; ?>
                        <?php if ($dueDate): ?>
                            <span class="asgn-card__due <?= $isOverdue ? '' : 'asgn-card__due--ok' ?>">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Due on: <?= $dueDate ?>
              </span>
                        <?php endif; ?>
                    </div>

                    <div class="asgn-card__footer">
            <span class="<?= $isDone ? 'badge-done' : 'badge-pending' ?>">
              <?= $isDone ? '✓ Done' : 'Pending' ?>
            </span>
                        <div class="asgn-card__actions">

                            <!-- Edit button -->
                            <a href="#" title="Edit"
                               data-bs-toggle="modal"
                               data-bs-target="#assignmentModal"
                               onclick="openEditModal(<?= $assignment->id ?>, <?= htmlspecialchars(json_encode([
                                       'title'      => $assignment->title,
                                       'due_date'   => $assignment->due_date,
                                       'is_done'    => $assignment->is_done,
                                       'course_id'  => $assignment->course_id,
                                       'teacher_id' => $assignment->teacher_id,
                               ]), ENT_QUOTES) ?>); return false;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>

                            <!-- Delete button -->
                            <?= Html::beginForm(Url::to(['assignment/delete', 'id' => $assignment->id]), 'post', ['style' => 'display:inline']) ?>
                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                            <button type="submit" class="btn-delete" title="Delete"
                                    onclick="return confirm('Delete this assignment?')">
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

<!-- ── Modal: Create / Edit Assignment ───────────────────────── -->
<div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="assignmentModalLabel">New Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="assignment-form" method="post">
                <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>

                <div class="modal-body d-flex flex-column gap-3">

                    <!-- Title -->
                    <div>
                        <label class="form-label" for="modal-title">Title</label>
                        <input type="text" id="modal-title" name="Assignment[title]"
                               class="form-control" placeholder="e.g. Solve this equation" required>
                    </div>

                    <!-- Subject / Course -->
                    <div>
                        <label class="form-label" for="modal-course">Subject</label>
                        <select id="modal-course" name="Assignment[course_id]" class="form-select" onchange="loadTeachers(this.value)">
                            <option value="">— Select subject —</option>
                            <?php if (!empty($courses ?? [])): ?>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= Html::encode($course->id) ?>"><?= Html::encode($course->course_name) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Teacher (dynamisch je nach Course) -->
                    <div>
                        <label class="form-label" for="modal-teacher">Teacher</label>
                        <select id="modal-teacher" name="Assignment[teacher_id]" class="form-select">
                            <option value="">— Select subject first —</option>
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="form-label" for="modal-due-date">Due on</label>
                        <input type="date" id="modal-due-date" name="Assignment[due_date]" class="form-control">
                    </div>

                    <!-- Done toggle -->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="modal-is-done"
                               name="Assignment[is_done]" value="1">
                        <label class="form-check-label" for="modal-is-done"
                               style="font-size:.85rem;color:#374151;">Mark as finished</label>
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
    const urlCreate = '<?= Url::to(['assignment/create']) ?>';
    const urlUpdate = (id) =>
        '<?= Url::to(['assignment/update', 'id' => '__ID__']) ?>'.replace('__ID__', id);
    const urlTeachersByCourse = '<?= Url::to(['assignment/teachers-by-course']) ?>';

    function loadTeachers(courseId, selectedTeacherId = null) {
        const select = document.getElementById('modal-teacher');
        select.innerHTML = '<option value="">— Loading... —</option>';

        if (!courseId) {
            select.innerHTML = '<option value="">— Select subject first —</option>';
            return;
        }

        fetch(urlTeachersByCourse + '?course_id=' + courseId)
            .then(r => r.json())
            .then(teachers => {
                select.innerHTML = '<option value="">— Select teacher —</option>';
                teachers.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.id;
                    opt.textContent = t.name;
                    if (selectedTeacherId && t.id == selectedTeacherId) opt.selected = true;
                    select.appendChild(opt);
                });
            });
    }

    function openCreateModal() {
        document.getElementById('assignmentModalLabel').textContent = 'New Assignment';
        document.getElementById('assignment-form').action = urlCreate;
        document.getElementById('modal-title').value = '';
        document.getElementById('modal-course').value = '';
        document.getElementById('modal-teacher').innerHTML = '<option value="">— Select subject first —</option>';
        document.getElementById('modal-due-date').value = '';
        document.getElementById('modal-is-done').checked = false;
    }

    function openEditModal(id, data) {
        document.getElementById('assignmentModalLabel').textContent = 'Edit Assignment';
        document.getElementById('assignment-form').action = urlUpdate(id);
        document.getElementById('modal-title').value    = data.title    ?? '';
        document.getElementById('modal-course').value   = data.course_id ?? '';
        document.getElementById('modal-due-date').value = data.due_date  ?? '';
        document.getElementById('modal-is-done').checked = !!parseInt(data.is_done);
        loadTeachers(data.course_id, data.teacher_id);
    }
</script>