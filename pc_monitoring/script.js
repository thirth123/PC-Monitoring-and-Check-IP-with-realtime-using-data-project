function fetchPCStatus() {
    fetch('get_status.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('labContainer');
            container.innerHTML = '';

            for (let lab = 1; lab <= 4; lab++) { // Updated to 4 labs
                const labPCs = data.filter(pc => pc.lab_id == lab);
                const labSection = document.createElement('div');
                labSection.className = 'lab-section mb-3';
                labSection.innerHTML = `
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#lab${lab}Collapse">
                                Lab ${lab} (${labPCs.length} PCs)
                            </button>
                        </h2>
                        <div id="lab${lab}Collapse" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="row">
                                    ${labPCs.map(pc => `
                                        <div class="col-md-3 mb-3">
                                            <div class="card card-custom ${pc.status === 'online' ? 'online' : 'offline'}">
                                                <div class="card-body">
                                                    <h5>PC ${pc.pc_id}</h5>
                                                    <p>Status: ${pc.status}</p>
                                                    <p>Last Check: ${pc.last_check}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(labSection);
            }
        });
}

function refreshStatus() {
    fetchPCStatus();
}

window.onload = fetchPCStatus;
setInterval(fetchPCStatus, 30000);