<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIDCRAFT NEURAL NETWORK - TENDER INTELLIGENCE MATRIX</title>
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Source+Code+Pro:wght@400;500;600;700&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
    <!-- Matrix Background Canvas -->
    <canvas id="matrix-canvas" class="matrix-background"></canvas>
    
    <!-- Matrix Loading Screen -->
    <div id="matrix-loader" class="matrix-loader">
        <div class="matrix-loading-text">
            <div class="typing-text">ACCESSING BIDCRAFT NEURAL NETWORK...</div>
            <div class="typing-text delay-1">ESTABLISHING SECURE CONNECTION...</div>
            <div class="typing-text delay-2">LOADING TENDER INTELLIGENCE MATRIX...</div>
            <div class="typing-text delay-3">WELCOME TO THE CONSTRUCT</div>
        </div>
    </div>
    
    <div class="container matrix-interface">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <img src="{{asset('/img/Bidcraft_Logo.png')}}" alt="BidCraft Logo" class="logo-image">
                </div>
                
            </div>
            <div class="welcome-text">
                Welcome, {{ ucfirst(Auth::user()->name) }} | <a href="{{ route('logout') }}" class="logout-link" style="color: #00ff41; text-decoration: none;">Logout</a>
            </div>
        </header>

        <!-- Matrix Neural Interface Description -->
        <div class="matrix-description">
            <div class="matrix-typing-text">
                <span class="matrix-prompt">SYSTEM://</span> 
                <span class="typing-effect">INITIALIZING TENDER INTELLIGENCE NEURAL NETWORK...</span>
            </div>
            <div class="matrix-status">
                <span class="status-indicator"></span>
                <span class="status-text">CONNECTED TO BIDCRAFT MATRIX</span>
            </div>
            <p class="matrix-instruction">
                INJECT YOUR PRODUCT PARAMETERS, COMPETITOR MATRICES, AND PRICING VECTORS INTO THE NEURAL CONSTRUCT. 
                THE SYSTEM WILL COMPUTE OPTIMAL TENDER STRATEGIES WITH PROFIT ALGORITHMS FROM THE DIGITAL REALM.
            </p>
        </div>

        <!-- Main Dashboard -->
        <main class="dashboard">
            <!-- Neural Product Matrix Section -->
            <section class="card product-info">
                <div class="card-header">
                    <h2><i class="fas fa-microchip"></i> PRODUCT_MATRIX.EXE</h2>
                    <div class="matrix-header-glow">DATA_INJECTION_NODE</div>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <select id="productName" class="form-control dropdown">
                                <option value="">Select Product</option>
                                <!-- Products will be loaded dynamically from CSV -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="registration">Registration Status</label>
                            <select id="registration" class="form-control dropdown">
                                <option value="">Select Status</option>
                                <option value="Registered">Registered</option>
                                <option value="Unregistered">Unregistered</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="country">🌍 Target Country</label>
                            <select id="country" class="form-control dropdown">
                                <option value="">Select Country</option>
                                <!-- Countries will be loaded dynamically from CSV -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="grade">Grade</label>
                            <select id="grade" class="form-control dropdown">
                                <option value="">Select Grade</option>
                                <option value="EP">EP</option>
                                <option value="EPN">EPN</option>
                                <option value="USP">USP</option>
                                <option value="BP">BP</option>
                                <option value="IH">IH</option>
                                <option value="BP-Dong">BP-Dong</option>
                                <option value="Outsource">Outsource</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="department">IB Department</label>
                            <select id="department" class="form-control dropdown">
                                <option value="">Select Department</option>
                                <option value="IB VPG Germany">IB VPG Germany</option>
                                <option value="IB VPG India">IB VPG India</option>
                                <option value="IB VRL LATAM">IB VRL LATAM</option>
                                <option value="IB VRL Africa">IB VRL Africa</option>
                                <option value="IB VRL SEA">IB VRL SEA</option>
                                <option value="IB VRL MEA">IB VRL MEA</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lastQuotedYear">Last Quoted Year</label>
                            <select id="lastQuotedYear" class="form-control dropdown">
                                <option value="">Select Year</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tenderQuantity">Tender Quantity</label>
                            <input type="number" id="tenderQuantity" class="form-control" placeholder="0" min="0">
                            <small class="text-muted">Number of units in the tender</small>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Client Neural Interface -->
            <section class="card client-info">
                <div class="card-header">
                    <h2><i class="fas fa-brain"></i> CLIENT_NEURAL_INTERFACE.EXE</h2>
                    <div class="matrix-header-glow">PRICING_ALGORITHMS</div>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="clientMargin">Client Margin (%)</label>
                            <input type="number" id="clientMargin" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="clientExpenses">Client Expenses (%)</label>
                            <input type="number" id="clientExpenses" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="tentativeFreight">Tentative Freight (%)</label>
                            <input type="number" id="tentativeFreight" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="foc">FOC</label>
                            <input type="number" id="foc" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="ibPP">IB Purchase Price (Euros)</label>
                            <input type="number" id="ibPP" class="form-control" placeholder="0.00" step="0.01" readonly>
                            <small class="text-muted">Price automatically calculated based on product, grade, and department selection</small>
                        </div>
                        <div class="form-group">
                            <label for="lastYearWinningPrize">Last Tender Winning Price</label>
                            <input type="number" id="lastYearWinningPrize" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="localPreference">Local Preference (%)</label>
                            <input type="number" id="localPreference" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="lastVRLCIFPrice">Last VRL CIF Price to Client</label>
                            <input type="number" id="lastVRLCIFPrice" class="form-control" placeholder="0.00" step="0.01">
                        </div>
                        <div class="form-group">
                            <label for="wasWinnerLocal">Was Winner Last Year Local?</label>
                            <select id="wasWinnerLocal" class="form-control dropdown">
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label for="supplyRemarks">Remarks for Supply Timelines</label>
                            <textarea id="supplyRemarks" class="form-control" rows="3" placeholder="Enter comments about supply timelines, delivery schedules, or other relevant remarks..."></textarea>
                            <small class="text-muted">Optional comments about supply conditions and timelines</small>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Registered Competitors -->
            <section class="card competitors-info">
                <div class="card-header">
                    <h2><i class="fas fa-users"></i> Registered Competitors</h2>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Manufacturing Company</th>
                                    <th>Country</th>
                                    <th>Marketing Company</th>
                                    <th>Country</th>
                                    <th>Saudi Agent</th>
                                    <th>CIF Price (USD)</th>
                                </tr>
                            </thead>
                            <tbody id="competitorsTableBody">
                                <tr>
                                    <td><input type="text" class="table-input" placeholder="Company name"></td>
                                    <td><input type="text" class="table-input" placeholder="Country"></td>
                                    <td><input type="text" class="table-input" placeholder="Marketing company"></td>
                                    <td><input type="text" class="table-input" placeholder="Country"></td>
                                    <td><input type="text" class="table-input" placeholder="Agent name"></td>
                                    <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-secondary add-row-btn" onclick="addCompetitorRow()">
                            <i class="fas fa-plus"></i> Add Competitor
                        </button>
                    </div>
                </div>
            </section>

            <!-- Export Data -->
            <section class="card export-data">
                <div class="card-header">
                    <h2><i class="fas fa-shipping-fast"></i> Export Data</h2>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Indian Co.</th>
                                    <th>Country</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Submitted Competitors</th>
                                </tr>
                            </thead>
                            <tbody id="exportTableBody">
                                <tr>
                                    <td><input type="text" class="table-input" placeholder="Company name"></td>
                                    <td><input type="text" class="table-input" placeholder="Country"></td>
                                    <td><input type="number" class="table-input" placeholder="0"></td>
                                    <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
                                    <td><input type="text" class="table-input" placeholder="Competitors"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-secondary add-row-btn" onclick="addExportRow()">
                            <i class="fas fa-plus"></i> Add Export Data
                        </button>
                    </div>
                </div>
            </section>

            <!-- Other Relevant Tenders -->
            <section class="card other-tenders">
                <div class="card-header">
                    <h2><i class="fas fa-clipboard-list"></i> Other Relevant Tenders</h2>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tender Name</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>VRL Quote</th>
                                    <th>1st Awardee Price</th>
                                    <th>1st Awardee Name</th>
                                    <th>2nd Awardee Price</th>
                                    <th>2nd Awardee Name</th>
                                </tr>
                            </thead>
                            <tbody id="otherTendersTableBody">
                                <tr>
                                    <td><input type="text" class="table-input" placeholder="Tender name"></td>
                                    <td><input type="text" class="table-input" placeholder="Product name"></td>
                                    <td><input type="number" class="table-input" placeholder="0"></td>
                                    <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
                                    <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
                                    <td><input type="text" class="table-input" placeholder="Awardee name"></td>
                                    <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
                                    <td><input type="text" class="table-input" placeholder="Awardee name"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-secondary add-row-btn" onclick="addOtherTenderRow()">
                            <i class="fas fa-plus"></i> Add Tender
                        </button>
                    </div>
                </div>
            </section>

            <!-- Self-Working Section -->
            <section class="card self-working">
                <div class="card-header">
                    <h2><i class="fas fa-user-edit"></i> 🛠️ Self-Working (Manual Calculation)</h2>
                    <div class="header-actions">
                        <button class="btn btn-secondary" onclick="calculateSelfWorking()">
                            <i class="fas fa-calculator"></i> Calculate Manual
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="swProduct">Product</label>
                            <input type="text" id="swProduct" class="form-control" readonly>
                            <small class="text-muted">Auto-filled from product selection above</small>
                        </div>
                        <div class="form-group">
                            <label for="swLastWinningPrice">Last Tender Winning Price</label>
                            <input type="number" id="swLastWinningPrice" class="form-control" readonly>
                            <small class="text-muted">Auto-filled from client details above</small>
                        </div>
                        <div class="form-group">
                            <label for="swReductionPercent">% Reduction (Manual Input)</label>
                            <input type="number" id="swReductionPercent" class="form-control" placeholder="0.00" step="0.01">
                            <small class="text-muted">Enter your manual reduction percentage</small>
                        </div>
                        <div class="form-group">
                            <label for="swClientMargin">Client Margin (%)</label>
                            <input type="number" id="swClientMargin" class="form-control" readonly>
                            <small class="text-muted">Auto-filled from client details above</small>
                        </div>
                        <div class="form-group">
                            <label for="swClientExpenses">Client Expenses (%)</label>
                            <input type="number" id="swClientExpenses" class="form-control" readonly>
                            <small class="text-muted">Auto-filled from client details above</small>
                        </div>
                        <div class="form-group">
                            <label for="swFreight">Freight (%)</label>
                            <input type="number" id="swFreight" class="form-control" readonly>
                            <small class="text-muted">Auto-filled from client details above</small>
                        </div>
                    </div>
                    
                    <!-- Self-Working Results -->
                    <div class="results-grid" style="margin-top: 25px;">
                        <div class="result-card">
                            <div class="result-label">Manual Final Bid to Ministry</div>
                            <div class="result-value" id="swFinalBid">$0.00</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">Manual CIF Without Commission</div>
                            <div class="result-value" id="swCifWithoutCommission">$0.00</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">Manual Ex Works Price</div>
                            <div class="result-value" id="swExWorksPrice">$0.00</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">Manual Gross Contribution (%)</div>
                            <div class="result-value" id="swGrossContribution">0%</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">Manual Profit Prediction</div>
                            <div class="result-value" id="swProfitPrediction">Calculate</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">Manual % Reduction from Last Price</div>
                            <div class="result-value" id="swReductionFromLast">0%</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Neural Analysis Core -->
            <section class="card analysis-results">
                <div class="card-header">
                    <h2><i class="fas fa-chart-line"></i> NEURAL_ANALYSIS_CORE.EXE</h2>
                    <div class="matrix-header-glow">QUANTUM_CALCULATIONS</div>
                    <div class="header-actions">
                        <button class="btn btn-primary" onclick="calculateBid()">
                            <i class="fas fa-bolt"></i> EXECUTE_MATRIX
                        </button>
                        <button class="btn btn-secondary" onclick="askAI()">
                            <i class="fas fa-brain"></i> ASK_AI
                        </button>
                        <button class="btn btn-secondary export-btn" onclick="exportToPDF()">
                            <i class="fas fa-download"></i> EXTRACT_DATA
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="results-grid">
                        <div class="result-card primary">
                            <div class="result-label">Final Bid to Ministry</div>
                            <div class="result-value" id="finalBid">$0.00</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">CIF Without Commission</div>
                            <div class="result-value" id="cifWithoutCommission">$0.00</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">Ex Works Price</div>
                            <div class="result-value" id="exWorksPrice">$0.00</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">Gross Contribution (%)</div>
                            <div class="result-value" id="grossContribution">0%</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">Profit Prediction</div>
                            <div class="result-value" id="profitPrediction">Calculate</div>
                        </div>
                        <div class="result-card">
                            <div class="result-label">% Reduction from Last Price</div>
                            <div class="result-value" id="reductionPercent">0%</div>
                        </div>
                    </div>
                    
                    <!-- Detailed Analysis Table -->
                    <div class="analysis-table-container">
                        <h3>Detailed Analysis</h3>
                        <table class="analysis-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Year Diff</th>
                                    <th>Price after Yearly Degradation</th>
                                    <th>Competitors Benchmarking</th>
                                    <th>Local Preference</th>
                                    <th>Final Bid</th>
                                    <th>Predictions</th>
                                </tr>
                            </thead>
                            <tbody id="analysisTableBody">
                                <tr>
                                    <td id="analysisProduct">-</td>
                                    <td id="yearDiff">-</td>
                                    <td id="priceAfterDegradation">$0.00</td>
                                    <td id="competitorsBenchmark">$0.00</td>
                                    <td id="localPrefCalc">$0.00</td>
                                    <td id="finalBidAnalysis">$0.00</td>
                                    <td id="predictionsAnalysis">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Comparison Section -->
            <section class="card comparison-results">
                <div class="card-header">
                    <h2><i class="fas fa-balance-scale"></i> ⚖️ Auto vs Manual Comparison</h2>
                    <div class="header-actions">
                        <button class="btn btn-info" onclick="generateComparison()">
                            <i class="fas fa-sync-alt"></i> Compare Results
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="comparison-container">
                        <table class="comparison-table">
                            <thead>
                                <tr>
                                    <th>Metric</th>
                                    <th>Auto-Calculated</th>
                                    <th>Manual (Self-Working)</th>
                                    <th>Difference</th>
                                    <th>Variance %</th>
                                </tr>
                            </thead>
                            <tbody id="comparisonTableBody">
                                <tr>
                                    <td><strong>Final Bid to Ministry</strong></td>
                                    <td id="compAutoFinalBid">-</td>
                                    <td id="compManualFinalBid">-</td>
                                    <td id="compDiffFinalBid">-</td>
                                    <td id="compVarFinalBid">-</td>
                                </tr>
                                <tr>
                                    <td>CIF Without Commission</td>
                                    <td id="compAutoCIF">-</td>
                                    <td id="compManualCIF">-</td>
                                    <td id="compDiffCIF">-</td>
                                    <td id="compVarCIF">-</td>
                                </tr>
                                <tr>
                                    <td>Ex Works Price</td>
                                    <td id="compAutoExWorks">-</td>
                                    <td id="compManualExWorks">-</td>
                                    <td id="compDiffExWorks">-</td>
                                    <td id="compVarExWorks">-</td>
                                </tr>
                                <tr>
                                    <td>Gross Contribution (%)</td>
                                    <td id="compAutoGross">-</td>
                                    <td id="compManualGross">-</td>
                                    <td id="compDiffGross">-</td>
                                    <td id="compVarGross">-</td>
                                </tr>
                                <tr>
                                    <td>Profit Prediction</td>
                                    <td id="compAutoProfit">-</td>
                                    <td id="compManualProfit">-</td>
                                    <td id="compDiffProfit">-</td>
                                    <td id="compVarProfit">-</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="comparison-summary" id="comparisonSummary" style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; display: none;">
                            <h4>📈 Comparison Summary</h4>
                            <p id="summaryText">Generate comparison to see analysis summary.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- AI Recommendations Section -->
            <section class="card ai-recommendations" id="aiRecommendationsSection" style="display: none;">
            <div class="card-header">
                    <h2><i class="fas fa-robot"></i> AI_RECOMMENDATIONS.EXE</h2>
                    <div class="matrix-header-glow">AI RESULTS</div>
            </div>
            <div class="card-body">
                     <!-- Loading Animation -->
                     <div id="aiLoadingSection" style="display: none; text-align: center; padding: 40px;">
                         <div class="ai-loader">
                             <div class="matrix-rain">
                                 <div class="rain-drop"></div>
                                 <div class="rain-drop"></div>
                                 <div class="rain-drop"></div>
                                 <div class="rain-drop"></div>
                                 <div class="rain-drop"></div>
                             </div>
                             <div class="ai-processing-text">
                                 <h3 style="color: #00ff41; margin: 20px 0 10px 0; font-family: 'Courier New', monospace;">
                                     <i class="fas fa-brain"></i> AI PROCESSING...
                                 </h3>
                                 <p style="color: #ffffff; margin: 0; font-family: 'Courier New', monospace;" id="loadingText">
                                     Analyzing pharmaceutical tender data...
                                 </p>
                                 <div class="progress-dots" style="margin-top: 15px;">
                                     <span style="color: #00ff41;">█</span>
                                     <span style="color: #00ff41;">█</span>
                                     <span style="color: #00ff41;">█</span>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <!-- AI Response Content -->
                     <div id="aiResponseContent" style="background: #1a1a1a; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; font-family: monospace; white-space: pre-wrap; max-height: 600px; overflow-y: auto; display: none;">
                    <!-- AI response will be displayed here -->
                </div>
            </div>
        </section>
        </main>

        <!-- Coming Soon Section -->
        <section class="coming-soon">
            <div class="coming-soon-content">
                <h3><i class="fas fa-rocket"></i> 🚀 Coming Soon</h3>
                <p>Exciting new features are on the way to enhance your BidCraft experience:</p>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-tachometer-alt"></i>
                        <h4>Tender Dashboard & Export Data</h4>
                        <p>Auto calculation and feed - automated data integration from tender and export sources</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-brain"></i>
                        <h4>AI-Based Recommendations</h4>
                        <p>Machine learning powered insights for optimal pricing strategies and market analysis</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-users-cog"></i>
                        <h4>Login & Approval Workflows</h4>
                        <p>Multi-user access controls with approval processes for enterprise-grade security</p>
                    </div>
                </div>
                <p class="coming-soon-note">Stay tuned for these powerful enhancements that will revolutionize your tender pricing workflow!</p>
            </div>
        </section>

        <!-- Matrix Neural Footer -->
        <footer class="matrix-footer">
            <div class="matrix-footer-content">
                <div class="matrix-copyright">
                    <span class="matrix-prompt">SYSTEM://</span> 
                    &copy; 2024 BIDCRAFT NEURAL NETWORK | VENUS_REMEDIES_LTD.EXE | <a href="{{ route('logout') }}" class="logout-link" style="color: #00ff41; text-decoration: none;">LOG OUT</a>
                </div>
                <div class="matrix-creator">
                    <span class="creator-tag">DESIGNED_IN_THE_CONSTRUCT_BY:</span>
                    <img src="{{asset('/img/AC_Creations_PNG.png')}}" alt="AC Creations Logo" class="ac-logo matrix-logo">
                </div>
            </div>
            <div class="matrix-footer-scan"></div>
        </footer>
    </div>

    <script src="{{asset('js/matrix-animation.js')}}"></script>
    <script src="{{asset('js/script.js')}}"></script>
    
    <style>
        /* AI Loading Animation Styles */
        .ai-loader {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .matrix-rain {
            position: relative;
            width: 100px;
            height: 80px;
            margin-bottom: 20px;
            overflow: hidden;
            border-radius: 8px;
            background: linear-gradient(45deg, #000000, #1a1a1a);
        }

        .rain-drop {
            position: absolute;
            width: 2px;
            height: 20px;
            background: linear-gradient(to bottom, transparent, #00ff41, transparent);
            animation: rain-fall 1.5s infinite linear;
            opacity: 0.8;
        }

        .rain-drop:nth-child(1) { left: 10%; animation-delay: 0s; }
        .rain-drop:nth-child(2) { left: 30%; animation-delay: 0.3s; }
        .rain-drop:nth-child(3) { left: 50%; animation-delay: 0.6s; }
        .rain-drop:nth-child(4) { left: 70%; animation-delay: 0.9s; }
        .rain-drop:nth-child(5) { left: 90%; animation-delay: 1.2s; }

        @keyframes rain-fall {
            0% { top: -20px; opacity: 0; }
            50% { opacity: 1; }
            100% { top: 100px; opacity: 0; }
        }

        .progress-dots span {
            animation: dots-blink 1.5s infinite;
            margin: 0 2px;
            font-size: 1.2em;
        }

        .progress-dots span:nth-child(1) { animation-delay: 0s; }
        .progress-dots span:nth-child(2) { animation-delay: 0.5s; }
        .progress-dots span:nth-child(3) { animation-delay: 1s; }

        @keyframes dots-blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }

        .ai-processing-text h3 {
            text-shadow: 0 0 10px #00ff41;
            animation: glow-pulse 2s infinite;
        }

        @keyframes glow-pulse {
            0%, 100% { text-shadow: 0 0 10px #00ff41; }
            50% { text-shadow: 0 0 20px #00ff41, 0 0 30px #00ff41; }
        }
    </style>

    <script>
        // Add AI functionality to existing calculateBid function
        function askAI() {
            // Collect all form data including missing fields
            const formData = {
                _token: '{{ csrf_token() }}',
                product_name: document.getElementById('productName').value,
                country: document.getElementById('country').value,
                registration: document.getElementById('registration').value,
                grade: document.getElementById('grade').value,
                department: document.getElementById('department').value,
                last_quoted_year: document.getElementById('lastQuotedYear').value,
                tender_quantity: document.getElementById('tenderQuantity').value,
                client_margin: document.getElementById('clientMargin').value,
                client_expenses: document.getElementById('clientExpenses').value,
                tentative_freight: document.getElementById('tentativeFreight').value,
                foc: document.getElementById('foc').value,
                ib_purchase_price: document.getElementById('ibPP').value,
                last_year_winning_prize: document.getElementById('lastYearWinningPrize').value,
                local_preference: document.getElementById('localPreference').value,
                last_vrl_cif_price: document.getElementById('lastVRLCIFPrice').value,
                was_winner_local: document.getElementById('wasWinnerLocal').value,
                supply_remarks: document.getElementById('supplyRemarks').value,
                
                // Missing fields that need to be added to form or collected dynamically
                authority: 'Ministry of Health', // Default value since not in form
                winner: 'Previous Winner', // Default value since not in form
                last_quantity: document.getElementById('tenderQuantity').value, // Use current quantity as fallback
                batch_size: '1000', // Default value since not in form
                batch_cost: '50', // Default value since not in form
            };

            // Collect export data from table
            formData.exports_data = collectExportData();
            
            // Collect competitors data from table
            formData.competitors_data = collectCompetitorsData();

            // Show loading state
            showAILoading();

            // Send AJAX request to AI endpoint
            fetch('{{ route("bid-recommendations.ask-ai") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                // Hide loading state
                hideAILoading();
                
                if (data.success) {
                    // Display AI response
                    showAIResponse(data.response);
                    
                    // Scroll to AI section
                    document.getElementById('aiRecommendationsSection').scrollIntoView({ 
                        behavior: 'smooth' 
                    });
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideAILoading();
                alert('Error calling AI service');
            });
        }

        // Collect export data from table
        function collectExportData() {
            const exports = [];
            const exportRows = document.querySelectorAll('#exportTableBody tr');
            
            exportRows.forEach(row => {
                const inputs = row.querySelectorAll('input');
                if (inputs.length >= 5) {
                    const company = inputs[0].value.trim();
                    const country = inputs[1].value.trim();
                    const quantity = inputs[2].value.trim();
                    const price = inputs[3].value.trim();
                    const competitors = inputs[4].value.trim();
                    
                    if (company || country || quantity || price) {
                        exports.push({
                            company: company || 'Unknown Company',
                            country: country || 'Unknown Country',
                            quantity: quantity || '0',
                            price: price || '0',
                            competitors: competitors || 'None'
                        });
                    }
                }
            });
            
            return exports.length > 0 ? exports : [{ 
                company: 'No export data', 
                country: 'N/A', 
                quantity: '0', 
                price: '0',
                competitors: 'None'
            }];
        }

        // Collect competitors data from table
        function collectCompetitorsData() {
            const competitors = [];
            const competitorRows = document.querySelectorAll('#competitorsTableBody tr');
            
            competitorRows.forEach(row => {
                const inputs = row.querySelectorAll('input');
                if (inputs.length >= 6) {
                    const mfgCompany = inputs[0].value.trim();
                    const mfgCountry = inputs[1].value.trim();
                    const mktCompany = inputs[2].value.trim();
                    const mktCountry = inputs[3].value.trim();
                    const agent = inputs[4].value.trim();
                    const cifPrice = inputs[5].value.trim();
                    
                    if (mfgCompany || mktCompany || agent) {
                        competitors.push({
                            manufacturing_company: mfgCompany || 'Unknown',
                            manufacturing_country: mfgCountry || 'Unknown',
                            marketing_company: mktCompany || 'Unknown',
                            marketing_country: mktCountry || 'Unknown',
                            saudi_agent: agent || 'Unknown',
                            cif_price: cifPrice || '0'
                        });
                    }
                }
            });
            
            return competitors.length > 0 ? competitors : [{ 
                manufacturing_company: 'No competitors data',
                manufacturing_country: 'N/A',
                marketing_company: 'N/A',
                marketing_country: 'N/A',
                saudi_agent: 'N/A',
                cif_price: '0'
            }];
        }

        // Show AI loading animation
        function showAILoading() {
            // Show the AI section
            document.getElementById('aiRecommendationsSection').style.display = 'block';
            
            // Show loading, hide response
            document.getElementById('aiLoadingSection').style.display = 'block';
            document.getElementById('aiResponseContent').style.display = 'none';
            
            // Start loading text animation
            startLoadingTextAnimation();
            
            // Scroll to AI section
            document.getElementById('aiRecommendationsSection').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        // Hide AI loading animation
        function hideAILoading() {
            document.getElementById('aiLoadingSection').style.display = 'none';
            stopLoadingTextAnimation();
        }

        // Show AI response
        function showAIResponse(response) {
            document.getElementById('aiResponseContent').textContent = response;
            document.getElementById('aiResponseContent').style.display = 'block';
        }

        // Loading text animation
        let loadingTextInterval;
        function startLoadingTextAnimation() {
            const loadingTexts = [
                'Analyzing pharmaceutical tender data...',
                'Processing competitor intelligence...',
                'Calculating pricing strategies...',
                'Evaluating geopolitical factors...',
                'Generating recommendations...',
                'Finalizing AI analysis...'
            ];
            let index = 0;
            
            loadingTextInterval = setInterval(() => {
                document.getElementById('loadingText').textContent = loadingTexts[index];
                index = (index + 1) % loadingTexts.length;
            }, 2000);
        }

        function stopLoadingTextAnimation() {
            if (loadingTextInterval) {
                clearInterval(loadingTextInterval);
            }
        }
    </script>
</body>
</html>
