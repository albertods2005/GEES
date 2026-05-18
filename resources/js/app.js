import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.createTacticalBoard = function createTacticalBoard(config) {
    return {
        storageKey: config.storageKey,
        sport: config.sport,
        canEdit: Boolean(config.canEdit),
        teamName: config.teamName,
        pieces: [],
        draggingId: null,
        dragOffsetX: 0,
        dragOffsetY: 0,
        isFullscreen: false,
        fullscreenSupported: false,
        counters: {
            team: 0,
            opponent: 0,
            ball: 0,
        },
        limits: {
            team: 11,
            opponent: 11,
            ball: 3,
        },
        get sportKey() {
            const normalizedSport = String(this.sport || '').toLowerCase();

            if (normalizedSport.includes('sala')) {
                return 'futsal';
            }

            if (normalizedSport.includes('baloncesto')) {
                return 'basketball';
            }

            return 'football';
        },
        get fieldClass() {
            return `public-board-${this.sportKey}`;
        },
        get boardTitle() {
            if (this.sportKey === 'futsal') {
                return 'Pista de futbol sala';
            }

            if (this.sportKey === 'basketball') {
                return 'Cancha de baloncesto';
            }

            return 'Campo de futbol';
        },
        init() {
            this.fullscreenSupported = Boolean(document.fullscreenEnabled);
            document.addEventListener('fullscreenchange', () => {
                this.isFullscreen = document.fullscreenElement === this.$refs.boardPanel;
            });

            const saved = window.localStorage.getItem(this.storageKey);

            if (saved) {
                try {
                    const parsed = JSON.parse(saved);
                    this.pieces = Array.isArray(parsed.pieces) ? parsed.pieces : [];
                    this.counters = {
                        team: Number(parsed.counters?.team || 0),
                        opponent: Number(parsed.counters?.opponent || 0),
                        ball: Number(parsed.counters?.ball || 0),
                    };
                } catch {
                    this.seedBoard();
                }
            } else {
                this.seedBoard();
            }
        },
        seedBoard() {
            const presets = {
                football: [
                    { id: 'team-1', type: 'team', label: '1', x: 18, y: 50 },
                    { id: 'team-2', type: 'team', label: '2', x: 32, y: 35 },
                    { id: 'team-3', type: 'team', label: '3', x: 32, y: 50 },
                    { id: 'team-4', type: 'team', label: '4', x: 32, y: 65 },
                    { id: 'opponent-1', type: 'opponent', label: 'R1', x: 76, y: 50 },
                    { id: 'ball-1', type: 'ball', label: 'B', x: 50, y: 50 },
                ],
                futsal: [
                    { id: 'team-1', type: 'team', label: '1', x: 22, y: 50 },
                    { id: 'team-2', type: 'team', label: '2', x: 36, y: 35 },
                    { id: 'team-3', type: 'team', label: '3', x: 36, y: 50 },
                    { id: 'team-4', type: 'team', label: '4', x: 36, y: 65 },
                    { id: 'opponent-1', type: 'opponent', label: 'R1', x: 74, y: 50 },
                    { id: 'ball-1', type: 'ball', label: 'B', x: 50, y: 50 },
                ],
                basketball: [
                    { id: 'team-1', type: 'team', label: '1', x: 24, y: 50 },
                    { id: 'team-2', type: 'team', label: '2', x: 34, y: 35 },
                    { id: 'team-3', type: 'team', label: '3', x: 34, y: 65 },
                    { id: 'opponent-1', type: 'opponent', label: 'R1', x: 72, y: 35 },
                    { id: 'opponent-2', type: 'opponent', label: 'R2', x: 72, y: 65 },
                    { id: 'ball-1', type: 'ball', label: 'B', x: 50, y: 50 },
                ],
            };

            this.pieces = presets[this.sportKey].map((piece) => ({ ...piece }));
            this.counters = {
                team: this.pieces.filter((piece) => piece.type === 'team').length,
                opponent: this.pieces.filter((piece) => piece.type === 'opponent').length,
                ball: this.pieces.filter((piece) => piece.type === 'ball').length,
            };
            this.persist();
        },
        persist() {
            window.localStorage.setItem(this.storageKey, JSON.stringify({
                pieces: this.pieces,
                counters: this.counters,
            }));
        },
        countPieces(type) {
            return this.pieces.filter((piece) => piece.type === type).length;
        },
        addPiece(type) {
            if (!this.canEdit) {
                return;
            }

            if (this.countPieces(type) >= this.limits[type]) {
                return;
            }

            this.counters[type] += 1;

            const labelMap = {
                team: String(this.counters.team),
                opponent: `R${this.counters.opponent}`,
                ball: 'B',
            };

            this.pieces.push({
                id: `${type}-${Date.now()}`,
                type,
                label: labelMap[type],
                x: 50,
                y: 50,
            });

            this.persist();
        },
        removePiece(id) {
            if (!this.canEdit) {
                return;
            }

            this.pieces = this.pieces.filter((piece) => piece.id !== id);
            this.persist();
        },
        startDrag(id, event) {
            if (!this.canEdit) {
                return;
            }

            const surface = this.$refs.surface;
            const target = event.currentTarget;
            const surfaceRect = surface.getBoundingClientRect();
            const pieceRect = target.getBoundingClientRect();

            this.draggingId = id;
            this.dragOffsetX = event.clientX - pieceRect.left;
            this.dragOffsetY = event.clientY - pieceRect.top;

            if (target.setPointerCapture) {
                target.setPointerCapture(event.pointerId);
            }

            document.body.style.userSelect = 'none';
            document.body.style.cursor = 'grabbing';
            surface.dataset.dragging = 'true';
            surface.dataset.surfaceWidth = String(surfaceRect.width);
        },
        onPointerMove(event) {
            if (!this.canEdit || !this.draggingId) {
                return;
            }

            const surface = this.$refs.surface;
            const rect = surface.getBoundingClientRect();
            const nextLeft = event.clientX - rect.left - this.dragOffsetX + 22;
            const nextTop = event.clientY - rect.top - this.dragOffsetY + 22;
            const x = Math.min(96, Math.max(4, (nextLeft / rect.width) * 100));
            const y = Math.min(94, Math.max(6, (nextTop / rect.height) * 100));
            const piece = this.pieces.find((entry) => entry.id === this.draggingId);

            if (!piece) {
                return;
            }

            piece.x = Number(x.toFixed(2));
            piece.y = Number(y.toFixed(2));
        },
        endDrag() {
            if (!this.draggingId) {
                return;
            }

            this.draggingId = null;
            document.body.style.userSelect = '';
            document.body.style.cursor = '';
            this.persist();
        },
        resetBoard() {
            if (!this.canEdit) {
                return;
            }

            this.seedBoard();
        },
        async toggleFullscreen() {
            if (!this.fullscreenSupported) {
                return;
            }

            if (document.fullscreenElement === this.$refs.boardPanel) {
                await document.exitFullscreen();
                return;
            }

            await this.$refs.boardPanel.requestFullscreen();
        },
    };
};

Alpine.start();
