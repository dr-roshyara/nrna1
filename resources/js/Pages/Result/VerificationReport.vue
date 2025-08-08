<template>
  <div class="verification-container">
    <h3 class="verification-title">Comprehensive Verification Report</h3>
    
    <div class="verification-grid">
      <!-- Discrepancy Analysis -->
      <div class="verification-section">
        <h4>Discrepancy Analysis</h4>
        <div class="discrepancy-details">
          <div v-for="(post, index) in postsWithDiscrepancies" :key="index" class="post-discrepancy">
            <h5>{{ post.name }}</h5>
            <table class="discrepancy-table">
              <thead>
                <tr>
                  <th>Candidate</th>
                  <th>Official</th>
                  <th>Raw</th>
                  <th>Difference</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(diff, candId) in post.discrepancies" :key="candId"
                    :class="{'severe-discrepancy': Math.abs(diff.official - diff.raw) > 2}">
                  <td>{{ getCandidateName(candId) }}</td>
                  <td>{{ diff.official }}</td>
                  <td>{{ diff.raw }}</td>
                  <td :class="{'text-red-500': diff.official < diff.raw, 'text-green-500': diff.official > diff.raw}">
                    {{ diff.official - diff.raw }}
                  </td>
                  <td>
                    <span v-if="Math.abs(diff.official - diff.raw) > 2" class="status-badge error">Critical</span>
                    <span v-else class="status-badge warning">Warning</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Investigation Tools -->
      <div class="verification-section">
        <h4>Investigation Tools</h4>
        <div class="toolbox">
          <button @click="exportDiscrepancies" class="tool-button">
            Export Discrepancies
          </button>
          <button @click="analyzePatterns" class="tool-button">
            Analyze Patterns
          </button>
          <div v-if="patternAnalysis" class="pattern-results">
            <h5>Pattern Analysis Results</h5>
            <pre>{{ patternAnalysis }}</pre>
          </div>
        </div>

        <div class="resolution-actions">
          <h5>Resolution Options</h5>
          <select v-model="resolutionAction" class="action-select">
            <option value="">Select action...</option>
            <option value="recount">Request Recount</option>
            <option value="adjust">Adjust Official Count</option>
            <option value="investigate">Launch Investigation</option>
          </select>
          <button @click="resolveDiscrepancies" class="resolve-button">
            Apply Resolution
          </button>
        </div>
      </div>
    </div>

    <!-- Audit Log -->
    <div class="audit-log">
      <h4>Verification History</h4>
      <ul>
        <li v-for="(log, index) in auditLog" :key="index">
          [{{ log.timestamp }}] {{ log.action }} - {{ log.details }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    posts: Array,
    finalResults: Object
  },
  data() {
    return {
      postsWithDiscrepancies: [],
      patternAnalysis: null,
      resolutionAction: '',
      auditLog: []
    };
  },
  methods: {
    getCandidateName(candId) {
      for (const post of this.posts) {
        const candidate = post.candidates.find(c => c.candidacy_id === candId);
        if (candidate) return candidate.user?.name || candidate.name;
      }
      return candId;
    },
    async analyzeDiscrepancies() {
      try {
        // Simulate API call - replace with actual axios call in your implementation
        const discrepancies = this.calculateDiscrepancies();
        this.postsWithDiscrepancies = this.posts.map(post => ({
          ...post,
          discrepancies: discrepancies[post.post_id] || {}
        }));
        
        this.logAction('Discrepancy analysis completed');
      } catch (error) {
        console.error("Analysis failed:", error);
        this.logAction('Analysis failed', 'error');
      }
    },
    calculateDiscrepancies() {
      // This should be replaced with your actual discrepancy calculation logic
      // or API call to backend
      const discrepancies = {};
      
      this.posts.forEach(post => {
        discrepancies[post.post_id] = {};
        post.candidates.forEach(candidate => {
          const official = this.finalResults[post.post_id]?.candidates
            ?.find(c => c.candidacy_id === candidate.candidacy_id)?.vote_count || 0;
          const raw = candidate.raw_votes || 0; // You'll need to get raw votes from somewhere
          
          if (official !== raw) {
            discrepancies[post.post_id][candidate.candidacy_id] = {
              official,
              raw
            };
          }
        });
      });
      
      return discrepancies;
    },
    async analyzePatterns() {
      try {
        // Simulate pattern analysis - replace with actual implementation
        this.patternAnalysis = "Pattern analysis would show if discrepancies follow specific patterns";
        this.logAction('Pattern analysis completed');
      } catch (error) {
        console.error("Pattern analysis failed:", error);
      }
    },
    exportDiscrepancies() {
      // Create a download link without file-saver
      const data = JSON.stringify(this.postsWithDiscrepancies, null, 2);
      const blob = new Blob([data], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      
      const a = document.createElement('a');
      a.href = url;
      a.download = `discrepancies-${new Date().toISOString()}.json`;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
      
      this.logAction('Exported discrepancies');
    },
    async resolveDiscrepancies() {
      if (!this.resolutionAction) return;
      
      try {
        // Simulate resolution - replace with actual implementation
        this.logAction(`Resolution applied: ${this.resolutionAction}`);
        alert(`Resolution "${this.resolutionAction}" would be processed`);
      } catch (error) {
        console.error("Resolution failed:", error);
        this.logAction('Resolution failed', 'error');
      }
    },
    logAction(action, type = 'info') {
      this.auditLog.unshift({
        timestamp: new Date().toLocaleString(),
        action,
        type
      });
    }
  },
  mounted() {
    this.analyzeDiscrepancies();
    this.logAction('Verification initialized');
  }
};
</script>

<style scoped>
.verification-container {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  padding: 1.5rem;
  margin-top: 2rem;
}

.verification-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.verification-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

@media (min-width: 1024px) {
  .verification-grid {
    grid-template-columns: 1fr 1fr;
  }
}

.verification-section {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  padding: 1rem;
}

.discrepancy-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 0.5rem;
  font-size: 0.875rem;
}

.discrepancy-table th, 
.discrepancy-table td {
  border: 1px solid #e5e7eb;
  padding: 0.5rem;
  text-align: left;
}

.discrepancy-table th {
  background-color: #f3f4f6;
}

.severe-discrepancy {
  background-color: #fef2f2;
}

.text-red-500 {
  color: #ef4444;
}

.text-green-500 {
  color: #10b981;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.status-badge.error {
  background-color: #fee2e2;
  color: #b91c1c;
}

.status-badge.warning {
  background-color: #fef3c7;
  color: #92400e;
}

.tool-button {
  padding: 0.5rem 1rem;
  background-color: #3b82f6;
  color: white;
  border-radius: 0.375rem;
  margin-right: 0.5rem;
  margin-bottom: 0.5rem;
  border: none;
  cursor: pointer;
}

.tool-button:hover {
  background-color: #2563eb;
}

.action-select {
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  padding: 0.5rem;
  margin-right: 0.5rem;
}

.resolve-button {
  padding: 0.5rem 1rem;
  background-color: #10b981;
  color: white;
  border-radius: 0.375rem;
  border: none;
  cursor: pointer;
}

.resolve-button:hover {
  background-color: #059669;
}

.audit-log {
  margin-top: 1.5rem;
  padding: 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
}

.audit-log ul {
  max-height: 10rem;
  overflow-y: auto;
}

.audit-log li {
  padding: 0.25rem 0;
  border-bottom: 1px solid #e5e7eb;
  font-size: 0.875rem;
}

.pattern-results pre {
  white-space: pre-wrap;
  background: #f3f4f6;
  padding: 0.5rem;
  border-radius: 0.25rem;
  margin-top: 0.5rem;
}
</style>