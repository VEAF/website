(function () {
    'use strict';

    // ========== STATE ==========
    var state = {
        flights: [],
        availablePlayers: [],
        aircraft: [],
        missions: {},
        dirty: false,
        saving: false,
        error: null
    };

    // ========== DOM REFERENCES ==========
    var editorEl = document.getElementById('ato-editor');
    if (!editorEl) return;

    var loadUrl = editorEl.dataset.loadUrl;
    var saveUrl = editorEl.dataset.saveUrl;
    var csrfToken = editorEl.dataset.csrfToken;
    var viewUrl = editorEl.dataset.viewUrl;

    // ========== MISSION CSS CLASSES ==========
    var missionClasses = {
        1: 'ato-editor__mission-badge--cap',
        2: 'ato-editor__mission-badge--cas',
        3: 'ato-editor__mission-badge--sead',
        4: 'ato-editor__mission-badge--escort',
        5: 'ato-editor__mission-badge--transport',
        6: 'ato-editor__mission-badge--recon',
        7: 'ato-editor__mission-badge--csar',
        8: 'ato-editor__mission-badge--tanker',
        9: 'ato-editor__mission-badge--awacs',
        10: 'ato-editor__mission-badge--fac'
    };

    // ========== HELPERS ==========
    function getAssignedPlayerIds() {
        var ids = {};
        state.flights.forEach(function (f) {
            (f.slots || []).forEach(function (s) {
                if (s.userId) ids[s.userId] = true;
            });
        });
        return ids;
    }

    function getUnassignedPlayers() {
        var assigned = getAssignedPlayerIds();
        return state.availablePlayers.filter(function (p) {
            return !assigned[p.id];
        });
    }

    function el(tag, className, textContent) {
        var element = document.createElement(tag);
        if (className) element.className = className;
        if (textContent !== undefined && textContent !== null) element.textContent = textContent;
        return element;
    }

    // ========== RENDER ==========
    function render() {
        editorEl.innerHTML = '';

        // Toolbar
        var toolbar = renderToolbar();
        editorEl.appendChild(toolbar);

        // Error
        if (state.error) {
            var errorDiv = el('div', 'alert alert-danger');
            errorDiv.textContent = state.error;
            var closeBtn = el('button', 'close');
            closeBtn.innerHTML = '&times;';
            closeBtn.onclick = function () {
                state.error = null;
                render();
            };
            errorDiv.prepend(closeBtn);
            editorEl.appendChild(errorDiv);
        }

        // Flights
        if (state.flights.length === 0) {
            var emptyMsg = el('div', 'alert alert-info', 'Aucun flight. Cliquez sur "Ajouter un flight" pour commencer.');
            editorEl.appendChild(emptyMsg);
        } else {
            state.flights.forEach(function (flight, index) {
                editorEl.appendChild(renderFlight(flight, index));
            });
        }

        // Player pool
        editorEl.appendChild(renderPlayerPool());

        // Init sortables
        initSortables();
    }

    function renderToolbar() {
        var toolbar = el('div', 'ato-editor__toolbar');

        var addBtn = el('button', 'btn btn-success');
        addBtn.innerHTML = '<i class="fa fa-plus"></i> Ajouter un flight';
        addBtn.onclick = function () {
            addFlight();
        };
        toolbar.appendChild(addBtn);

        var rightGroup = el('div');

        var saveBtn = el('button', 'btn btn-danger ml-2');
        saveBtn.innerHTML = '<i class="fa fa-save"></i> Enregistrer';
        if (state.saving) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Enregistrement...';
        }
        saveBtn.onclick = function () {
            save();
        };
        rightGroup.appendChild(saveBtn);

        toolbar.appendChild(rightGroup);
        return toolbar;
    }

    function renderFlight(flight, index) {
        var card = el('div', 'ato-editor__flight');

        // Header
        var header = el('div', 'ato-editor__flight-header');

        // Name input
        var nameInput = el('input', 'form-control form-control-sm');
        nameInput.type = 'text';
        nameInput.placeholder = 'Nom du flight';
        nameInput.value = flight.name || '';
        nameInput.style.width = '120px';
        nameInput.onchange = function () {
            updateFlightField(index, 'name', this.value);
        };
        header.appendChild(nameInput);

        // Aircraft select
        var aircraftSelect = el('select', 'form-control form-control-sm');
        aircraftSelect.style.width = '160px';
        var defaultOpt = el('option', null, '-- Appareil --');
        defaultOpt.value = '';
        aircraftSelect.appendChild(defaultOpt);
        state.aircraft.forEach(function (ac) {
            var opt = el('option', null, ac.name);
            opt.value = ac.id;
            if (flight.aircraftId == ac.id) opt.selected = true;
            aircraftSelect.appendChild(opt);
        });
        aircraftSelect.onchange = function () {
            updateFlightField(index, 'aircraftId', this.value ? parseInt(this.value) : null);
        };
        header.appendChild(aircraftSelect);

        // Mission select
        var missionSelect = el('select', 'form-control form-control-sm');
        missionSelect.style.width = '140px';
        var mDefaultOpt = el('option', null, '-- Mission --');
        mDefaultOpt.value = '';
        missionSelect.appendChild(mDefaultOpt);
        Object.keys(state.missions).forEach(function (key) {
            var opt = el('option', null, state.missions[key]);
            opt.value = key;
            if (flight.mission !== null && flight.mission !== undefined && flight.mission == key) opt.selected = true;
            missionSelect.appendChild(opt);
        });
        missionSelect.onchange = function () {
            updateFlightField(index, 'mission', this.value !== '' ? parseInt(this.value) : null);
        };
        header.appendChild(missionSelect);

        // Slots number
        var slotsLabel = el('span', 'ml-2 mr-1', 'Slots:');
        header.appendChild(slotsLabel);
        var slotsInput = el('input', 'form-control form-control-sm');
        slotsInput.type = 'number';
        slotsInput.min = '1';
        slotsInput.max = '20';
        slotsInput.value = flight.nbSlots || 4;
        slotsInput.style.width = '60px';
        slotsInput.onchange = function () {
            updateFlightField(index, 'nbSlots', parseInt(this.value) || 4);
        };
        header.appendChild(slotsInput);

        // Delete button
        var deleteBtn = el('button', 'btn btn-sm btn-outline-danger ml-auto');
        deleteBtn.innerHTML = '<i class="fa fa-trash"></i>';
        deleteBtn.title = 'Supprimer ce flight';
        deleteBtn.onclick = function () {
            if (confirm('Supprimer le flight "' + (flight.name || 'sans nom') + '" ?')) {
                removeFlight(index);
            }
        };
        header.appendChild(deleteBtn);

        card.appendChild(header);

        // Bases row
        var basesRow = el('div', 'ato-editor__flight-bases');
        var depInput = el('input', 'form-control form-control-sm d-inline-block mr-2');
        depInput.type = 'text';
        depInput.placeholder = 'Base de départ';
        depInput.value = flight.departureBase || '';
        depInput.style.width = '160px';
        depInput.onchange = function () {
            updateFlightField(index, 'departureBase', this.value || null);
        };
        var depIcon = el('span', 'mr-1');
        depIcon.innerHTML = '<i class="fa fa-plane-departure"></i>';
        basesRow.appendChild(depIcon);
        basesRow.appendChild(depInput);

        var retInput = el('input', 'form-control form-control-sm d-inline-block');
        retInput.type = 'text';
        retInput.placeholder = 'Base de retour';
        retInput.value = flight.returnBase || '';
        retInput.style.width = '160px';
        retInput.onchange = function () {
            updateFlightField(index, 'returnBase', this.value || null);
        };
        var retIcon = el('span', 'ml-2 mr-1');
        retIcon.innerHTML = '<i class="fa fa-plane-arrival"></i>';
        basesRow.appendChild(retIcon);
        basesRow.appendChild(retInput);
        card.appendChild(basesRow);

        // Slots body (drop zone)
        var body = el('div', 'ato-editor__flight-body');
        body.dataset.flightIndex = index;

        // Render assigned slots
        (flight.slots || []).forEach(function (slot, slotIndex) {
            var slotEl = renderSlotBadge(slot, index, slotIndex);
            body.appendChild(slotEl);
        });

        // Render empty slot indicators
        var assignedCount = (flight.slots || []).length;
        var totalSlots = flight.nbSlots || 4;
        for (var i = assignedCount; i < totalSlots; i++) {
            var emptySlot = el('span', 'ato-editor__empty-slot', 'vide');
            body.appendChild(emptySlot);
        }

        card.appendChild(body);

        // Guest player input
        var guestRow = el('div', 'ato-editor__flight-bases d-flex align-items-center');
        var guestInput = el('input', 'form-control form-control-sm mr-2');
        guestInput.type = 'text';
        guestInput.placeholder = 'Ajouter un invité...';
        guestInput.style.width = '200px';
        var guestBtn = el('button', 'btn btn-sm btn-outline-info');
        guestBtn.innerHTML = '<i class="fa fa-user-plus"></i>';
        guestBtn.title = 'Ajouter un joueur invité';
        guestBtn.onclick = function () {
            var name = guestInput.value.trim();
            if (name) {
                addGuestSlot(index, name);
                guestInput.value = '';
            }
        };
        guestInput.onkeypress = function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                guestBtn.click();
            }
        };
        guestRow.appendChild(guestInput);
        guestRow.appendChild(guestBtn);
        card.appendChild(guestRow);

        return card;
    }

    function renderSlotBadge(slot, flightIndex, slotIndex) {
        var cssClass = 'ato-editor__slot';
        if (!slot.isRegistered && !slot.userId) {
            cssClass += ' ato-editor__slot--guest';
        }
        var slotEl = el('span', cssClass);
        slotEl.dataset.userId = slot.userId || '';
        slotEl.dataset.username = slot.username || '';
        slotEl.dataset.isRegistered = slot.isRegistered ? '1' : '0';
        slotEl.textContent = slot.username || '?';

        // Remove button
        var removeBtn = el('i', 'fa fa-times');
        removeBtn.onclick = function (e) {
            e.stopPropagation();
            removeSlot(flightIndex, slotIndex);
        };
        slotEl.appendChild(removeBtn);

        return slotEl;
    }

    function renderPlayerPool() {
        var section = el('div', 'ato-editor__player-pool');

        var title = el('div', 'ato-editor__player-pool-title');
        title.innerHTML = '<i class="fa fa-users"></i> Joueurs disponibles';
        section.appendChild(title);

        var unassigned = getUnassignedPlayers();

        if (unassigned.length === 0 && state.availablePlayers.length === 0) {
            var noPlayers = el('div', 'text-muted small', 'Aucun joueur inscrit (oui/peut-être) pour cet événement.');
            section.appendChild(noPlayers);
        } else if (unassigned.length === 0) {
            var allAssigned = el('div', 'text-muted small', 'Tous les joueurs sont assignés.');
            section.appendChild(allAssigned);
        }

        var poolContainer = el('div', 'ato-editor__pool-items');
        poolContainer.id = 'player-pool';
        unassigned.forEach(function (player) {
            var playerEl = el('span', 'ato-editor__slot' + (player.voteType === 'maybe' ? ' ato-editor__slot--maybe' : ''));
            playerEl.dataset.userId = player.id;
            playerEl.dataset.username = player.nickname;
            playerEl.dataset.isRegistered = '1';
            playerEl.textContent = player.nickname;
            if (player.voteType === 'maybe') {
                playerEl.title = 'Peut-être';
            }
            poolContainer.appendChild(playerEl);
        });
        section.appendChild(poolContainer);

        return section;
    }

    // ========== SORTABLE INIT ==========
    function initSortables() {
        // Player pool
        var poolEl = document.getElementById('player-pool');
        if (poolEl) {
            new Sortable(poolEl, {
                group: {
                    name: 'players',
                    pull: true,
                    put: true
                },
                sort: false,
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function (evt) {
                    handleDragEnd(evt);
                }
            });
        }

        // Each flight body
        var flightBodies = editorEl.querySelectorAll('.ato-editor__flight-body');
        flightBodies.forEach(function (bodyEl) {
            new Sortable(bodyEl, {
                group: {
                    name: 'players',
                    pull: true,
                    put: true
                },
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                filter: '.ato-editor__empty-slot',
                onAdd: function (evt) {
                    handleDragEnd(evt);
                },
                onRemove: function (evt) {
                    handleDragEnd(evt);
                },
                onUpdate: function (evt) {
                    handleDragEnd(evt);
                }
            });
        });
    }

    function handleDragEnd() {
        // Rebuild state from DOM
        var flightBodies = editorEl.querySelectorAll('.ato-editor__flight-body');
        flightBodies.forEach(function (bodyEl) {
            var flightIndex = parseInt(bodyEl.dataset.flightIndex);
            var newSlots = [];
            var slotEls = bodyEl.querySelectorAll('.ato-editor__slot');
            slotEls.forEach(function (slotEl) {
                var userId = slotEl.dataset.userId ? parseInt(slotEl.dataset.userId) : null;
                newSlots.push({
                    userId: userId || null,
                    username: slotEl.dataset.username || null,
                    isRegistered: slotEl.dataset.isRegistered === '1'
                });
            });
            if (state.flights[flightIndex]) {
                state.flights[flightIndex].slots = newSlots;
            }
        });
        state.dirty = true;
        render();
    }

    // ========== ACTIONS ==========
    function addFlight() {
        state.flights.push({
            id: null,
            name: 'Flight ' + (state.flights.length + 1),
            mission: null,
            aircraftId: null,
            aircraftName: null,
            nbSlots: 4,
            departureBase: null,
            returnBase: null,
            slots: []
        });
        state.dirty = true;
        render();
    }

    function removeFlight(index) {
        state.flights.splice(index, 1);
        state.dirty = true;
        render();
    }

    function updateFlightField(index, field, value) {
        state.flights[index][field] = value;
        if (field === 'aircraftId') {
            var ac = state.aircraft.find(function (a) { return a.id == value; });
            state.flights[index].aircraftName = ac ? ac.name : null;
        }
        state.dirty = true;
    }

    function removeSlot(flightIndex, slotIndex) {
        state.flights[flightIndex].slots.splice(slotIndex, 1);
        state.dirty = true;
        render();
    }

    function addGuestSlot(flightIndex, username) {
        state.flights[flightIndex].slots.push({
            userId: null,
            username: username,
            isRegistered: false
        });
        state.dirty = true;
        render();
    }

    // ========== SAVE ==========
    function save() {
        if (state.saving) return;
        state.saving = true;
        state.error = null;
        render();

        var payload = {
            flights: state.flights.map(function (f) {
                return {
                    id: f.id > 0 ? f.id : null,
                    name: f.name || 'sans nom',
                    mission: f.mission,
                    aircraftId: f.aircraftId,
                    nbSlots: f.nbSlots || 4,
                    departureBase: f.departureBase,
                    returnBase: f.returnBase,
                    slots: (f.slots || []).map(function (s) {
                        return {
                            userId: s.userId || null,
                            username: s.userId ? null : s.username
                        };
                    })
                };
            })
        };

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify(payload)
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                state.saving = false;
                if (data.success) {
                    state.dirty = false;
                    $.notify('Changements enregistrés', 'success');
                    loadData();
                } else {
                    state.error = data.error || 'Erreur lors de la sauvegarde';
                    render();
                }
            })
            .catch(function (err) {
                state.saving = false;
                state.error = 'Erreur réseau: ' + err.message;
                render();
            });
    }

    // ========== LOAD ==========
    function loadData() {
        fetch(loadUrl)
            .then(function (response) { return response.json(); })
            .then(function (data) {
                state.flights = data.flights || [];
                state.availablePlayers = data.availablePlayers || [];
                state.aircraft = data.aircraft || [];
                state.missions = data.missions || {};
                state.dirty = false;
                state.error = null;
                render();
            })
            .catch(function (err) {
                state.error = 'Erreur de chargement: ' + err.message;
                render();
            });
    }

    // ========== UNSAVED CHANGES WARNING ==========
    window.addEventListener('beforeunload', function (e) {
        if (state.dirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // ========== INIT ==========
    loadData();
})();
