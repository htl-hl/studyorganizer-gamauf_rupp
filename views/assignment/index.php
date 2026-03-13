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