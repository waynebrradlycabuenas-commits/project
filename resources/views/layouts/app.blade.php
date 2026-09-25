<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura — Minimalist Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --border-color: #cbd5e1; /* Clearer, solid slate border */
            --border-strong: #0f172a;
            --text-primary: #0f172a;
            --text-secondary: #475569;
        }

        .dark {
            --bg-primary: #09090b;
            --bg-secondary: #121215;
            --bg-card: #18181b;
            --border-color: #3f3f46; /* Clearer, solid dark mode border */
            --border-strong: #f4f4f5;
            --text-primary: #f4f4f5;
            --text-secondary: #a1a1aa;
        }

        /* Solid & Clear Card Styling */
        .clean-card {
            background-color: var(--bg-card);
            border: 2px solid var(--border-color);
            transition: border-color 0.2s ease, transform 0.2s ease;
        }
        .clean-card:hover {
            border-color: var(--border-strong);
        }

        /* Solid & Clear Form Input */
        .clean-input {
            background-color: var(--bg-secondary);
            border: 2px solid var(--border-color);
            color: var(--text-primary);
            transition: border-color 0.2s ease;
        }
        .clean-input:focus {
            outline: none;
            border-color: var(--border-strong);
        }

        /* Solid Button styling */
        .solid-btn {
            border: 2px solid var(--border-strong);
            transition: transform 0.1s ease, background-color 0.1s ease;
        }
        .solid-btn:active {
            transform: translateY(1px);
        }

        /* Custom smooth scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); border-left: 2px solid var(--border-color); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); }
        ::-webkit-scrollbar-thumb:hover { background: var(--border-strong); }
    </style>
</head>
<body id="appBody" class="min-h-screen flex flex-col">

    <!-- Top Navigation Bar -->
    <header class="border-b-2 border-[var(--border-color)] bg-[var(--bg-secondary)] sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[var(--border-strong)] text-[var(--bg-secondary)] flex items-center justify-center font-bold text-sm border-2 border-[var(--border-strong)]">
                    A
                </div>
                <div>
                    <h1 class="font-bold text-lg tracking-tight">badayos</h1>
                    <p class="text-xs text-[var(--text-secondary)] font-semibold">task manager</p>
                </div>
            </div>

            <!-- Global Search & Actions -->
            <div class="flex items-center gap-3">
                <div class="relative hidden sm:block w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-[var(--text-secondary)]">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" id="searchInput" oninput="handleSearch()" placeholder="Search tasks..." class="w-full clean-input pl-10 pr-4 py-2 rounded-xl text-xs font-semibold">
                </div>
                <button onclick="toggleDarkMode()" class="w-10 h-10 rounded-xl clean-input flex items-center justify-center text-xs font-semibold hover:border-[var(--border-strong)] transition-colors" title="Toggle Theme">
                    <i id="themeIcon" class="fa-solid fa-moon"></i>
                </button>
                <button onclick="openCreateModal()" class="px-4 py-2 rounded-xl bg-[var(--border-strong)] text-[var(--bg-secondary)] text-xs font-bold tracking-wide flex items-center gap-2 solid-btn">
                    <i class="fa-solid fa-plus text-[10px]"></i> New Task
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Grid Layout -->
    <main class="max-w-6xl w-full mx-auto p-6 md:p-10 flex-1 space-y-8">

        <!-- Balance Metrics Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="clean-card rounded-2xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-[var(--text-secondary)] uppercase tracking-wider">Total Tasks</p>
                    <h3 id="statTotal" class="text-3xl font-bold tracking-tight mt-1">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl border-2 border-[var(--border-color)] bg-[var(--bg-primary)] flex items-center justify-center text-[var(--text-secondary)]">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
            </div>
            <div class="clean-card rounded-2xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-[var(--text-secondary)] uppercase tracking-wider">In Progress</p>
                    <h3 id="statPending" class="text-3xl font-bold tracking-tight mt-1 text-blue-600 dark:text-blue-400">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl border-2 border-blue-300 bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-spinner text-sm"></i>
                </div>
            </div>
            <div class="clean-card rounded-2xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-[var(--text-secondary)] uppercase tracking-wider">Completed</p>
                    <h3 id="statCompleted" class="text-3xl font-bold tracking-tight mt-1 text-emerald-600 dark:text-emerald-400">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl border-2 border-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-check text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Navigation Bar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b-2 border-[var(--border-color)]">
            <!-- Tabs -->
            <div class="flex items-center gap-1.5 bg-[var(--bg-secondary)] p-1.5 rounded-xl border-2 border-[var(--border-color)]">
                <button onclick="setFilter('all')" id="tab-all" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all bg-[var(--border-strong)] text-[var(--bg-secondary)]">All Tasks</button>
                <button onclick="setFilter('pending')" id="tab-pending" class="px-4 py-1.5 rounded-lg text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all">In Progress</button>
                <button onclick="setFilter('completed')" id="tab-completed" class="px-4 py-1.5 rounded-lg text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all">Completed</button>
            </div>

            <!-- Secondary Dropdowns -->
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select id="categoryFilter" onchange="renderTasks()" class="clean-input px-3 py-2 rounded-xl text-xs font-bold">
                    <option value="all">All Categories</option>
                    <option value="Work">Work / School</option>
                    <option value="Personal">Personal</option>
                </select>
                <select id="priorityFilter" onchange="renderTasks()" class="clean-input px-3 py-2 rounded-xl text-xs font-bold">
                    <option value="all">All Priorities</option>
                    <option value="Urgent">Urgent</option>
                    <option value="Reminder">Reminder</option>
                </select>
            </div>
        </div>

        <!-- Task Grid Container -->
        <div>
            <!-- Empty State -->
            <div id="emptyState" class="hidden py-24 text-center clean-card rounded-2xl">
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl border-2 border-[var(--border-color)] bg-[var(--bg-primary)] flex items-center justify-center text-[var(--text-secondary)] text-sm">
                    <i class="fa-regular fa-clipboard"></i>
                </div>
                <h3 class="font-bold text-sm uppercase">No tasks found</h3>
                <p class="text-xs text-[var(--text-secondary)] font-semibold mt-1">Get started by creating a new task.</p>
            </div>

            <!-- Grid -->
            <div id="taskGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Injected dynamically -->
            </div>
        </div>

    </main>

    <!-- Clean Modal Form Overlay -->
    <div id="taskModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="clean-card bg-[var(--bg-card)] rounded-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-200 shadow-2xl" id="modalCard">
            <div class="flex items-center justify-between px-6 py-4 border-b-2 border-[var(--border-color)]">
                <h3 id="modalTitle" class="font-bold text-sm tracking-tight uppercase">Create New Task</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg clean-input flex items-center justify-center text-[var(--text-secondary)] hover:border-[var(--border-strong)] transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
            <form id="taskForm" onsubmit="handleFormSubmit(event)" class="p-6 space-y-4">
                <input type="hidden" id="taskId">
                <div>
                    <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1 uppercase tracking-wider">Title *</label>
                    <input type="text" id="taskTitle" required placeholder="e.g., Prepare quarterly review..." class="w-full clean-input px-3.5 py-2.5 rounded-xl text-xs font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1 uppercase tracking-wider">Description</label>
                    <textarea id="taskDesc" rows="3" placeholder="Add optional details..." class="w-full clean-input px-3.5 py-2.5 rounded-xl text-xs font-semibold resize-none"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1 uppercase tracking-wider">Category</label>
                        <select id="taskCategory" class="w-full clean-input px-3 py-2 rounded-xl text-xs font-semibold">
                            <option value="Work">Work</option>
                            <option value="Personal">Personal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1 uppercase tracking-wider">Priority</label>
                        <select id="taskPriority" class="w-full clean-input px-3 py-2 rounded-xl text-xs font-semibold">
                            <option value="Reminder">Reminder</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[var(--text-secondary)] mb-1 uppercase tracking-wider">Deadline *</label>
                        <input type="date" id="taskDueDate" required class="w-full clean-input px-2 py-2 rounded-xl text-[11px] font-semibold">
                    </div>
                </div>
                <div class="pt-4 border-t-2 border-[var(--border-color)] flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl clean-input text-xs font-bold uppercase hover:border-[var(--border-strong)] transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[var(--border-strong)] text-[var(--bg-secondary)] text-xs font-bold uppercase solid-btn">Save Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Application Engine Script -->
    <script>
        let tasks = [];
        let currentFilter = 'all';
        let currentSearchQuery = '';

        window.onload = function() {
            document.getElementById('taskDueDate').min = new Date().toISOString().split('T')[0];
            renderApp();
        };

        function toggleDarkMode() {
            const body = document.getElementById('appBody');
            const icon = document.getElementById('themeIcon');
            if (body.classList.contains('dark')) {
                body.classList.remove('dark');
                icon.className = "fa-solid fa-moon";
            } else {
                body.classList.add('dark');
                icon.className = "fa-solid fa-sun";
            }
        }

        function setFilter(filter) {
            currentFilter = filter;
            ['all', 'pending', 'completed'].forEach(f => {
                const btn = document.getElementById(`tab-${f}`);
                if (f === filter) {
                    btn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all bg-[var(--border-strong)] text-[var(--bg-secondary)]";
                } else {
                    btn.className = "px-4 py-1.5 rounded-lg text-xs font-bold text-[var(--text-secondary)] hover:text-[var(--text-primary)] transition-all";
                }
            });
            renderTasks();
        }

        function handleSearch() {
            currentSearchQuery = document.getElementById('searchInput').value.toLowerCase().trim();
            renderTasks();
        }

        function renderApp() {
            updateStats();
            renderTasks();
        }

        function updateStats() {
            const total = tasks.length;
            const pending = tasks.filter(t => t.status === 'pending').length;
            const completed = tasks.filter(t => t.status === 'completed').length;

            document.getElementById('statTotal').innerText = total;
            document.getElementById('statPending').innerText = pending;
            document.getElementById('statCompleted').innerText = completed;
        }

        function renderTasks() {
            const categoryVal = document.getElementById('categoryFilter').value;
            const priorityVal = document.getElementById('priorityFilter').value;

            const filtered = tasks.filter(t => {
                if (currentFilter !== 'all' && t.status !== currentFilter) return false;
                if (categoryVal !== 'all' && t.category !== categoryVal) return false;
                if (priorityVal !== 'all' && t.priority !== priorityVal) return false;
                if (currentSearchQuery && !t.title.toLowerCase().includes(currentSearchQuery) && !t.description.toLowerCase().includes(currentSearchQuery)) return false;
                return true;
            });

            const grid = document.getElementById('taskGrid');
            const emptyState = document.getElementById('emptyState');
            grid.innerHTML = '';

            if (filtered.length === 0) {
                emptyState.classList.remove('hidden');
                grid.classList.add('hidden');
                return;
            } else {
                emptyState.classList.add('hidden');
                grid.classList.remove('hidden');
            }

            filtered.forEach(task => {
                const isCompleted = task.status === 'completed';
                const card = document.createElement('div');
                card.className = "clean-card rounded-2xl p-5 flex flex-col justify-between";
                card.innerHTML = `
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border border-[var(--border-color)] bg-[var(--bg-primary)] text-[var(--text-secondary)]">${task.category}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase border ${task.priority === 'Urgent' ? 'border-red-300 bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'border-amber-300 bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400'}">${task.priority}</span>
                            </div>
                            <span class="text-[10px] font-bold text-[var(--text-secondary)]"><i class="fa-regular fa-calendar mr-1"></i>${task.dueDate}</span>
                        </div>
                        <h4 class="font-bold text-sm mb-1.5 ${isCompleted ? 'line-through opacity-50' : ''}">${escapeHtml(task.title)}</h4>
                        <p class="text-xs text-[var(--text-secondary)] font-semibold line-clamp-2">${escapeHtml(task.description || 'No additional details provided.')}</p>
                    </div>
                    <div class="mt-5 pt-3 border-t-2 border-[var(--border-color)] flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase ${isCompleted ? 'text-emerald-600 dark:text-emerald-400' : 'text-blue-600 dark:text-blue-400'}">
                            <span class="w-2 h-2 rounded-full ${isCompleted ? 'bg-emerald-600 dark:bg-emerald-400' : 'bg-blue-600 dark:bg-blue-400'}"></span>
                            ${isCompleted ? 'Completed' : 'In Progress'}
                        </span>
                        <div class="flex items-center gap-1.5">
                            <button onclick="toggleStatus('${task.id}')" title="${isCompleted ? 'Reopen' : 'Complete'}" class="w-7 h-7 rounded-lg clean-input flex items-center justify-center text-xs hover:border-[var(--border-strong)] transition-colors">
                                <i class="fa-solid ${isCompleted ? 'fa-rotate-left' : 'fa-check'}"></i>
                            </button>
                            <button onclick="openEditModal('${task.id}')" title="Edit" class="w-7 h-7 rounded-lg clean-input flex items-center justify-center text-xs hover:border-[var(--border-strong)] transition-colors">
                                <i class="fa-solid fa-pen text-[10px]"></i>
                            </button>
                            <button onclick="deleteTask('${task.id}')" title="Delete" class="w-7 h-7 rounded-lg clean-input flex items-center justify-center text-xs hover:text-red-600 hover:border-red-400 transition-colors">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
            updateStats();
        }

        function openModal() {
            const modal = document.getElementById('taskModal');
            const card = document.getElementById('modalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function openCreateModal() {
            document.getElementById('taskId').value = '';
            document.getElementById('taskForm').reset();
            document.getElementById('modalTitle').innerText = 'Create New Task';
            openModal();
        }

        function openEditModal(id) {
            const task = tasks.find(t => t.id === id);
            if (!task) return;
            document.getElementById('taskId').value = task.id;
            document.getElementById('taskTitle').value = task.title;
            document.getElementById('taskDesc').value = task.description;
            document.getElementById('taskCategory').value = task.category;
            document.getElementById('taskPriority').value = task.priority;
            document.getElementById('taskDueDate').value = task.dueDate;
            document.getElementById('modalTitle').innerText = 'Edit Task';
            openModal();
        }

        function closeModal() {
            const modal = document.getElementById('taskModal');
            const card = document.getElementById('modalCard');
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            const id = document.getElementById('taskId').value;
            const title = document.getElementById('taskTitle').value.trim();
            const description = document.getElementById('taskDesc').value.trim();
            const category = document.getElementById('taskCategory').value;
            const priority = document.getElementById('taskPriority').value;
            const dueDate = document.getElementById('taskDueDate').value;

            if (!title || !dueDate) return;

            if (id) {
                tasks = tasks.map(t => t.id === id ? { ...t, title, description, category, priority, dueDate } : t);
            } else {
                tasks.unshift({
                    id: Date.now().toString(),
                    title, description, category, priority, dueDate,
                    status: 'pending'
                });
            }
            closeModal();
            renderApp();
        }

        function toggleStatus(id) {
            tasks = tasks.map(t => t.id === id ? { ...t, status: t.status === 'completed' ? 'pending' : 'completed' } : t);
            renderApp();
        }

        function deleteTask(id) {
            tasks = tasks.filter(t => t.id !== id);
            renderApp();
        }

        function escapeHtml(str) {
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>
</html>