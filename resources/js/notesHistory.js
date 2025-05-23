document.addEventListener('DOMContentLoaded', function() {
    const notesHistory = document.getElementById('notes-history');
    
    if (!notesHistory) return;
    
    // Get the selected date from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    let selectedDate = urlParams.get('date') || new Date().toISOString().split('T')[0];
    
    // Update heading to show selected date
    const notesHeading = document.querySelector('.bg-white.rounded-lg.shadow-md.p-4.mt-4 h2');
    if (notesHeading) {
        notesHeading.innerHTML = `Notes for <span class="font-medium">${formatDate(selectedDate)}</span>`;
    }
    
    // Initial load of notes for the selected date
    loadNotes(selectedDate);
    
    function loadNotes(date) {
        notesHistory.innerHTML = '<p class="text-gray-500 text-center py-4">Loading notes...</p>';
        
        // Debug - log the date being requested
        console.log("Fetching notes for date:", date);
        
        
        fetch(`/notes?date=${date}`)
            .then(response => response.json())
            .then(data => {
                // Debug - log the notes received
                console.log("Notes received:", data.notes);
                
                notesHistory.innerHTML = '';
                
                if (data.notes && data.notes.length > 0) {
                    data.notes.forEach(note => {
                        const noteEl = document.createElement('div');
                        noteEl.className = 'p-3 bg-gray-50 rounded-lg mb-2';
                        noteEl.innerHTML = `
                            <div class="flex justify-between text-sm">
                                <span class="font-medium">${formatDate(note.date)}</span>
                            </div>
                            <p class="mt-1">${note.notes}</p>
                        `;
                        notesHistory.appendChild(noteEl);
                    });
                } else {
                    notesHistory.innerHTML = `<p class="text-gray-500 text-center py-4">No notes for ${formatDate(date)}</p>`;
                }
            })
            .catch(error => {
                console.error('Error loading notes:', error);
                notesHistory.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load notes</p>';
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