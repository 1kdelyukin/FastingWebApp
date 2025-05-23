document.addEventListener('DOMContentLoaded', function() {
    const saveNoteBtn = document.getElementById('save-note');
    const noteInput = document.getElementById('note-input');
    const notesHistory = document.getElementById('notes-history');
    
    // Default to today's date
    let selectedDate = new Date().toISOString().split('T')[0];
    
    // Load notes for today initially
    loadNotes(selectedDate);
    
    // Listen for date selection events from the calendar
    document.addEventListener('dateSelected', function(e) {
        selectedDate = e.detail.date; // Format should be YYYY-MM-DD
        loadNotes(selectedDate);
    });
    
    // Save note 
    if (saveNoteBtn) {
        saveNoteBtn.addEventListener('click', function() {
            const note = noteInput.value.trim();
            if (note) {
                saveNote(note, selectedDate);
            }
        });
    }
    
    function saveNote(noteText, date) {
        fetch('/notes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                recordDate: date,
                note: noteText
            })
        })
        .then(response => response.json())
        .then(data => {
            noteInput.value = '';
            loadNotes(date); // Reload notes for the selected date
        });
    }
    
    function loadNotes(date) {
        fetch(`/notes?date=${date}`)
            .then(response => response.json())
            .then(data => {
                notesHistory.innerHTML = '';
                
                if (data.notes && data.notes.length > 0) {
                    data.notes.forEach(note => {
                        const noteEl = document.createElement('div');
                        noteEl.className = 'p-3 bg-gray-50 rounded';
                        noteEl.innerHTML = `
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">${formatDate(note.date)}</span>
                            </div>
                            <p class="mt-1">${note.notes}</p>
                        `;
                        notesHistory.appendChild(noteEl);
                    });
                } else {
                    notesHistory.innerHTML = `<p class="text-gray-500 text-center">No notes for ${formatDate(date)}</p>`;
                }
            });
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }
});