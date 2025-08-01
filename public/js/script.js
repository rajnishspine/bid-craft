// BidCraft - Tender Pricing Dashboard JavaScript
class BidCraft {
    constructor() {
        this.currentYear = new Date().getFullYear();
        this.productData = [];
        this.countryData = [];
        this.pricingData = [];
        this.selfWorkingResults = {};
        this.initializeData();
        this.initializeEventListeners();
        this.initializeGSAPAnimations();
    }

    // Initialize CSV data
    async initializeData() {
        try {
            await this.loadPricingData(); // Load pricing data first
            await this.loadProductData(); // Then load products from pricing data
            await this.loadCountryData();
        } catch (error) {
            console.warn('Could not load CSV data, using fallback data');
            this.loadFallbackData();
        }
    }

    // Load product data from CSV
    async loadProductData() {
        // Get unique products from pricing data, using the exact format from CSV
        const uniqueProducts = [...new Set(this.pricingData.map(item => item.product))].sort();
        
        const productSelect = document.getElementById('productName');
        uniqueProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product;
            option.textContent = product;
            productSelect.appendChild(option);
        });
    }

    // Load country data from CSV
    async loadCountryData() {
        const countries = [
            { name: 'BOLIVIA', continent: 'SOUTH AMERICA' }, { name: 'BOTSWANA', continent: 'AFRICA' }, 
            { name: 'BRUNEI', continent: 'ASIA' }, { name: 'CAMBODIA', continent: 'ASIA' }, 
            { name: 'CHILE', continent: 'SOUTH AMERICA' }, { name: 'COLOMBIA', continent: 'SOUTH AMERICA' },
            { name: 'COSTA RICA', continent: 'NORTH AMERICA' }, { name: 'CUBA', continent: 'NORTH AMERICA' },
            { name: 'DOMINICAN REPUBLIC', continent: 'NORTH AMERICA' }, { name: 'ECUADOR', continent: 'SOUTH AMERICA' },
            { name: 'ETHIOPIA', continent: 'AFRICA' }, { name: 'GCC', continent: 'ASIA' }, 
            { name: 'GUATEMALA', continent: 'NORTH AMERICA' }, { name: 'HAITI', continent: 'NORTH AMERICA' },
            { name: 'INDONESIA', continent: 'ASIA' }, { name: 'JAMAICA', continent: 'NORTH AMERICA' },
            { name: 'JORDAN', continent: 'ASIA' }, { name: 'KENYA', continent: 'AFRICA' },
            { name: 'KUWAIT', continent: 'ASIA' }, { name: 'MALAYSIA', continent: 'ASIA' },
            { name: 'MEXICO', continent: 'NORTH AMERICA' }, { name: 'NIGERIA', continent: 'AFRICA' },
            { name: 'OMAN', continent: 'ASIA' }, { name: 'PAKISTAN', continent: 'ASIA' },
            { name: 'PANAMA', continent: 'NORTH AMERICA' }, { name: 'PERU', continent: 'SOUTH AMERICA' },
            { name: 'PHILIPPINES', continent: 'ASIA' }, { name: 'QATAR', continent: 'ASIA' },
            { name: 'SAUDI ARABIA', continent: 'ASIA' }, { name: 'SRI LANKA', continent: 'ASIA' },
            { name: 'THAILAND', continent: 'ASIA' }, { name: 'UGANDA', continent: 'AFRICA' },
            { name: 'VENEZUELA', continent: 'SOUTH AMERICA' }, { name: 'VIETNAM', continent: 'ASIA' },
            { name: 'YEMEN', continent: 'ASIA' }, { name: 'ZAMBIA', continent: 'AFRICA' },
            { name: 'AUSTRALIA', continent: 'OCEANIA' }, { name: 'BAHRAIN', continent: 'ASIA' },
            { name: 'UNITED ARAB EMIRATES', continent: 'ASIA' }, { name: 'SOUTH AFRICA', continent: 'AFRICA' },
            { name: 'SINGAPORE', continent: 'ASIA' }, { name: 'TURKEY', continent: 'ASIA' }
        ];
        
        const countrySelect = document.getElementById('country');
        
        // Group by continent
        const continents = {};
        countries.forEach(country => {
            if (!continents[country.continent]) {
                continents[country.continent] = [];
            }
            continents[country.continent].push(country.name);
        });
        
        // Add options grouped by continent
        Object.keys(continents).sort().forEach(continent => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = `🌍 ${continent}`;
            
            continents[continent].sort().forEach(countryName => {
                const option = document.createElement('option');
                option.value = countryName;
                option.textContent = `${this.getCountryFlag(countryName)} ${countryName}`;
                optgroup.appendChild(option);
            });
            
            countrySelect.appendChild(optgroup);
        });
    }

    // Get country flag emoji
    getCountryFlag(countryName) {
        const flags = {
            'BOLIVIA': '🇧🇴', 'BOTSWANA': '🇧🇼', 'BRUNEI': '🇧🇳', 'CAMBODIA': '🇰🇭',
            'CHILE': '🇨🇱', 'COLOMBIA': '🇨🇴', 'COSTA RICA': '🇨🇷', 'CUBA': '🇨🇺',
            'ECUADOR': '🇪🇨', 'ETHIOPIA': '🇪🇹', 'GCC': '🏛️', 'GUATEMALA': '🇬🇹',
            'INDONESIA': '🇮🇩', 'JAMAICA': '🇯🇲', 'JORDAN': '🇯🇴', 'KENYA': '🇰🇪',
            'KUWAIT': '🇰🇼', 'MALAYSIA': '🇲🇾', 'MEXICO': '🇲🇽', 'NIGERIA': '🇳🇬',
            'OMAN': '🇴🇲', 'PAKISTAN': '🇵🇰', 'PANAMA': '🇵🇦', 'PERU': '🇵🇪',
            'PHILIPPINES': '🇵🇭', 'QATAR': '🇶🇦', 'SAUDI ARABIA': '🇸🇦', 'SRI LANKA': '🇱🇰',
            'THAILAND': '🇹🇭', 'UGANDA': '🇺🇬', 'VENEZUELA': '🇻🇪', 'VIETNAM': '🇻🇳',
            'YEMEN': '🇾🇪', 'ZAMBIA': '🇿🇲', 'AUSTRALIA': '🇦🇺', 'BAHRAIN': '🇧🇭',
            'UNITED ARAB EMIRATES': '🇦🇪', 'SOUTH AFRICA': '🇿🇦', 'SINGAPORE': '🇸🇬', 'TURKEY': '🇹🇷'
        };
        return flags[countryName] || '🌍';
    }

    // Load fallback data if CSV loading fails
    loadFallbackData() {
        const productSelect = document.getElementById('productName');
        const fallbackProducts = ['AMIKACIN-500MG/2ML', 'AZITHROMYCIN-500MG', 'MEROPENEM-1G', 'VANCOMYCIN-500MG'];
        
        fallbackProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product;
            option.textContent = product;
            productSelect.appendChild(option);
        });
    }

    // Load pricing data from CSV
    async loadPricingData() {
        // Complete pricing data from CSV with exact format matching
        this.pricingData = [
            // IB VPG Germany
            { product: 'CEFTAZIDIME - 1G', grade: 'EP', price: 0.68, department: 'IB VPG Germany' },
            { product: 'CEFTAZIDIME - 2G', grade: 'EP', price: 1.34, department: 'IB VPG Germany' },
            { product: 'CEFTRIAXONE - 1G', grade: 'EP', price: 0.31, department: 'IB VPG Germany' },
            { product: 'CEFTRIAXONE - 1G', grade: 'EPN', price: 0.56, department: 'IB VPG Germany' },
            { product: 'CEFTRIAXONE - 2G', grade: 'EP', price: 0.53, department: 'IB VPG Germany' },
            { product: 'CEFTRIAXONE - 2G', grade: 'EPN', price: 0.79, department: 'IB VPG Germany' },
            { product: 'CEFUROXIME - 1.5G', grade: 'EP', price: 0.53, department: 'IB VPG Germany' },
            { product: 'CEFUROXIME - 750MG', grade: 'EP', price: 0.32, department: 'IB VPG Germany' },
            { product: 'IMIPENEM AND CILASTATIN - 500MG', grade: 'USP', price: 1.72, department: 'IB VPG Germany' },
            { product: 'MEROPENEM - 1G', grade: 'EP', price: 1.14, department: 'IB VPG Germany' },
            { product: 'MEROPENEM - 2G', grade: 'EP', price: 2.43, department: 'IB VPG Germany' },
            { product: 'MEROPENEM - 500MG', grade: 'EP', price: 0.65, department: 'IB VPG Germany' },
            { product: 'CARBOPLATIN - 150MG/15ML', grade: 'BP', price: 5.17, department: 'IB VPG Germany' },
            { product: 'CARBOPLATIN - 450MG/45ML', grade: 'BP', price: 14.96, department: 'IB VPG Germany' },
            { product: 'CISPLATIN - 100MG/100ML', grade: 'BP', price: 4.11, department: 'IB VPG Germany' },
            { product: 'CISPLATIN - 10MG/10ML', grade: 'BP', price: 0.99, department: 'IB VPG Germany' },
            { product: 'CISPLATIN - 50MG/50ML', grade: 'BP', price: 2.69, department: 'IB VPG Germany' },
            { product: 'CISPLATIN - 50MG/100ML', grade: 'BP', price: 2.74, department: 'IB VPG Germany' },
            { product: 'DOCETAXEL - 20MG/0.5ML (Single Vial)', grade: 'EP', price: 1.98, department: 'IB VPG Germany' },
            { product: 'GEMCITABINE - 1G', grade: 'EP', price: 5.02, department: 'IB VPG Germany' },
            { product: 'GEMCITABINE - 200MG', grade: 'EP', price: 1.98, department: 'IB VPG Germany' },
            
            // IB VPG India
            { product: 'CEFUROXIME - 750MG', grade: 'USP', price: 0.32, department: 'IB VPG India' },
            { product: 'CEFUROXIME - 750MG', grade: 'EP', price: 0.34, department: 'IB VPG India' },
            { product: 'CEFTRIAXONE - 500MG', grade: 'USP', price: 0.16, department: 'IB VPG India' },
            { product: 'CEFTRIAXONE - 1G', grade: 'USP', price: 0.21, department: 'IB VPG India' },
            { product: 'CEFTRIAXONE - 2G', grade: 'USP', price: 0.42, department: 'IB VPG India' },
            { product: 'CEFTRIAXONE - 500MG', grade: 'EP', price: 0.21, department: 'IB VPG India' },
            { product: 'CEFTRIAXONE - 1G', grade: 'EP', price: 0.33, department: 'IB VPG India' },
            { product: 'CEFTRIAXONE - 2G', grade: 'EP', price: 0.66, department: 'IB VPG India' },
            { product: 'CEFTRIAXONE AND SULBACTAM - 1.5G', grade: 'USP', price: 0.34, department: 'IB VPG India' },
            { product: 'CEFTAZIDIME - 1G', grade: 'USP', price: 0.45, department: 'IB VPG India' },
            { product: 'CEFTAZIDIME - 2G', grade: 'USP', price: 0.69, department: 'IB VPG India' },
            { product: 'CEFTAZIDIME - 1G', grade: 'EP', price: 0.69, department: 'IB VPG India' },
            { product: 'CEFTAZIDIME - 2G', grade: 'EP', price: 1.68, department: 'IB VPG India' },
            { product: 'AZTREONAM - 1G', grade: 'USP', price: 1.03, department: 'IB VPG India' },
            { product: 'MEROPENEM - 500MG', grade: 'USP', price: 0.59, department: 'IB VPG India' },
            { product: 'MEROPENEM - 1G', grade: 'USP', price: 1.1, department: 'IB VPG India' },
            { product: 'MEROPENEM - 2G', grade: 'USP', price: 2.28, department: 'IB VPG India' },
            { product: 'MEROPENEM - 500MG', grade: 'EP', price: 0.75, department: 'IB VPG India' },
            { product: 'MEROPENEM - 1G', grade: 'EP', price: 1.44, department: 'IB VPG India' },
            { product: 'MEROPENEM - 2G', grade: 'EP', price: 2.98, department: 'IB VPG India' },
            { product: 'VANCOMYCIN - 1G', grade: 'USP', price: 1.0, department: 'IB VRL LATAM' },
            { product: 'VANCOMYCIN - 500MG', grade: 'USP', price: 0.61, department: 'IB VRL LATAM' },
            
            // IB VRL MEA (includes AZTREONAM example you mentioned)
            { product: 'AZTREONAM - 1G', grade: 'USP', price: 1.1, department: 'IB VRL MEA' },
            { product: 'AZTREONAM - 2G', grade: 'USP', price: 1.72, department: 'IB VRL MEA' },
            { product: 'CEFEPIME - 1G', grade: 'USP', price: 0.72, department: 'IB VRL MEA' },
            { product: 'CEFEPIME - 2G', grade: 'USP', price: 1.13, department: 'IB VRL MEA' },
            { product: 'CEFTRIAXONE - 1G', grade: 'USP', price: 0.22, department: 'IB VRL MEA' },
            { product: 'CEFTRIAXONE - 1G', grade: 'EP', price: 0.35, department: 'IB VRL MEA' },
            { product: 'CEFTAZIDIME - 1G', grade: 'USP', price: 0.49, department: 'IB VRL MEA' },
            { product: 'CEFTAZIDIME - 1G', grade: 'EP', price: 0.74, department: 'IB VRL MEA' },
            { product: 'MEROPENEM - 1G', grade: 'USP', price: 1.18, department: 'IB VRL MEA' },
            { product: 'MEROPENEM - 1G', grade: 'EP', price: 1.54, department: 'IB VRL MEA' },
            { product: 'VANCOMYCIN - 1G', grade: 'USP', price: 1.07, department: 'IB VRL MEA' },
            { product: 'VANCOMYCIN - 500MG', grade: 'USP', price: 0.65, department: 'IB VRL MEA' },
            
            // IB VRL Africa
            { product: 'AZTREONAM - 1G', grade: 'USP', price: 1.1, department: 'IB VRL Africa' },
            { product: 'AZTREONAM - 2G', grade: 'USP', price: 1.72, department: 'IB VRL Africa' },
            { product: 'CEFEPIME - 1G', grade: 'USP', price: 0.72, department: 'IB VRL Africa' },
            { product: 'CEFTRIAXONE - 1G', grade: 'USP', price: 0.22, department: 'IB VRL Africa' },
            { product: 'MEROPENEM - 1G', grade: 'USP', price: 1.18, department: 'IB VRL Africa' },
            
            // IB VRL SEA
            { product: 'AZTREONAM - 1G', grade: 'USP', price: 1.1, department: 'IB VRL SEA' },
            { product: 'AZTREONAM - 2G', grade: 'USP', price: 1.72, department: 'IB VRL SEA' },
            { product: 'CEFEPIME - 1G', grade: 'USP', price: 0.72, department: 'IB VRL SEA' },
            { product: 'CEFTRIAXONE - 1G', grade: 'USP', price: 0.22, department: 'IB VRL SEA' },
            { product: 'MEROPENEM - 1G', grade: 'USP', price: 1.18, department: 'IB VRL SEA' },
            
            // IB VRL LATAM
            { product: 'AZITHROMYCIN - 500MG', grade: 'USP', price: 0.71, department: 'IB VRL LATAM' },
            { product: 'TEICOPLANIN - 200MG', grade: 'IH', price: 1.59, department: 'IB VRL LATAM' },
            { product: 'PIPERACILINE AND TAZOBACTAM - 4.5G', grade: 'IH', price: 0.93, department: 'IB VRL LATAM' },
            { product: 'VANCOMYCIN - 1G', grade: 'USP', price: 1.0, department: 'IB VRL LATAM' },
            { product: 'VANCOMYCIN - 500MG', grade: 'USP', price: 0.61, department: 'IB VRL LATAM' }
        ];
    }

    // Calculate IB Purchase Price based on selections
    calculateIBPurchasePrice() {
        const productName = document.getElementById('productName').value;
        const grade = document.getElementById('grade').value;
        const department = document.getElementById('department').value;
        const ibPPField = document.getElementById('ibPP');
        
        if (!productName || !grade || !department) {
            ibPPField.value = '';
            ibPPField.placeholder = 'Select product, grade, and department';
            return 0;
        }
        
        console.log('Looking for:', { productName, grade, department });
        
        // Find matching price in the data
        const match = this.pricingData.find(item => 
            item.product === productName && 
            item.grade === grade && 
            item.department === department
        );
        
        console.log('Match found:', match);
        
        if (match) {
            // Set the price directly
            ibPPField.value = match.price.toFixed(2);
            ibPPField.style.backgroundColor = '#e8f5e8';
            ibPPField.style.borderColor = '#10b981';
            
            // Only show status for successful price calculation
            // Determine currency based on department
            const currency = this.getCurrencyForDepartment(department);
            const currencySymbol = currency === 'EUR' ? '€' : '$';
            
            this.showStatusMessage(`✓ Price found: ${currencySymbol}${match.price.toFixed(2)} (${currency})`, 'success');
            
            return match.price;
        } else {
            ibPPField.value = '';
            ibPPField.style.backgroundColor = '#fff3cd';
            ibPPField.style.borderColor = '#f59e0b';
            
            // Log available combinations for debugging only
            const availableForProduct = this.pricingData.filter(item => item.product === productName);
            console.log('Available combinations for', productName, ':', availableForProduct);
            
            return 0;
        }
    }

    // Initialize all event listeners
    initializeEventListeners() {
        // Add real-time calculation on input changes
        const inputs = document.querySelectorAll('.form-control, .table-input');
        inputs.forEach(input => {
            input.addEventListener('input', () => this.debounce(this.calculateBid.bind(this), 500)());
            input.addEventListener('change', () => this.calculateBid());
        });

        // Add specific listeners for product info fields to update IB Purchase Price
        const productInfoFields = ['productName', 'grade', 'department'];
        productInfoFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('change', () => {
                    setTimeout(() => {
                        this.calculateIBPurchasePrice();
                        this.updateSelfWorkingFields();
                        if (fieldId === 'department') {
                            this.updateIBPriceLabel();
                        }
                    }, 100); // Small delay to ensure dropdown value is set
                });
            }
        });
        
        // Add listeners for client details to update self-working
        const clientFields = ['clientMargin', 'clientExpenses', 'tentativeFreight', 'lastYearWinningPrize'];
        clientFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('change', () => {
                    this.updateSelfWorkingFields();
                });
            }
        });
        
        // Add listener for manual reduction input
        const reductionField = document.getElementById('swReductionPercent');
        if (reductionField) {
            reductionField.addEventListener('input', () => {
                this.calculateSelfWorking();
            });
        }

        // Add enter key support for calculations
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) {
                this.calculateBid();
            }
        });
    }

    // Debounce function to limit API calls
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize GSAP animations
    initializeGSAPAnimations() {
        // Initial page load animations
        this.animatePageLoad();
        
        // Set up interactive animations
        this.setupFormAnimations();
        this.setupButtonAnimations();
        this.setupCardAnimations();
    }
    
    // Simplified page load sequence
    animatePageLoad() {
        // Quick fade in for entire page
        gsap.fromTo('body', 
            { opacity: 0 },
            { opacity: 1, duration: 0.3, ease: 'power2.out' }
        );
    }
    
    // Simplified form field animations
    setupFormAnimations() {
        // Only add subtle focus effects that don't interfere with functionality
        const formControls = document.querySelectorAll('.form-control, .table-input');
        
        formControls.forEach(input => {
            // Don't interfere with dropdown functionality
            if (input.tagName === 'SELECT') return;
            
            input.addEventListener('focus', () => {
                input.style.borderColor = '#3b82f6';
                input.style.boxShadow = '0 0 0 3px rgba(59, 130, 246, 0.1)';
            });
            
            input.addEventListener('blur', () => {
                input.style.borderColor = '';
                input.style.boxShadow = '';
            });
        });
    }
    
    // Simplified button animations
    setupButtonAnimations() {
        const buttons = document.querySelectorAll('.btn');
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', () => {
                button.style.transform = 'translateY(-1px)';
                button.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
            });
            
            button.addEventListener('mouseleave', () => {
                button.style.transform = '';
                button.style.boxShadow = '';
            });
        });
    }
    
    // Minimal card animations
    setupCardAnimations() {
        // Remove heavy card animations to prevent interference
    }
    
    

    // Get form values
    getFormValues() {
        return {
            productName: document.getElementById('productName').value,
            grade: document.getElementById('grade').value,
            department: document.getElementById('department').value,
            registration: document.getElementById('registration').value,
            country: document.getElementById('country').value,
            lastQuotedYear: parseInt(document.getElementById('lastQuotedYear').value) || 0,
            tenderQuantity: parseInt(document.getElementById('tenderQuantity').value) || 0,
            clientMargin: parseFloat(document.getElementById('clientMargin').value) || 0,
            clientExpenses: parseFloat(document.getElementById('clientExpenses').value) || 0,
            tentativeFreight: parseFloat(document.getElementById('tentativeFreight').value) || 0,
            foc: parseFloat(document.getElementById('foc').value) || 0,
            ibPP: parseFloat(document.getElementById('ibPP').value) || 0,
            lastYearWinningPrize: parseFloat(document.getElementById('lastYearWinningPrize').value) || 0,
            localPreference: parseFloat(document.getElementById('localPreference').value) || 0,
            lastVRLCIFPrice: parseFloat(document.getElementById('lastVRLCIFPrice').value) || 0,
            wasWinnerLocal: document.getElementById('wasWinnerLocal').value,
            supplyRemarks: document.getElementById('supplyRemarks').value
        };
    }

    // Calculate year difference from last quoted year
    calculateYearDifference(lastQuotedYear) {
        if (!lastQuotedYear) return 0;
        return this.currentYear - lastQuotedYear;
    }

    // Calculate yearly degradation in price based on Excel formula
    calculateYearlyDegradation(yearsDiff, lastVRLCIFPrice) {
        if (!lastVRLCIFPrice || yearsDiff === 0) return lastVRLCIFPrice;

        let degradationRate;
        if (yearsDiff === 1) {
            degradationRate = 0.93; // 93%
        } else if (yearsDiff === 2) {
            degradationRate = 0.86; // 86%
        } else if (yearsDiff === 3) {
            degradationRate = 0.79; // 79%
        } else if (yearsDiff >= 4) {
            degradationRate = 0.72; // 72%
        } else {
            degradationRate = 1.0; // No degradation
        }

        return lastVRLCIFPrice * degradationRate;
    }

    // Calculate competitors benchmarking based on number of competitors
    calculateCompetitorsBenchmarking(priceAfterDegradation) {
        const competitorRows = document.querySelectorAll('#competitorsTableBody tr');
        const numberOfCompetitors = Array.from(competitorRows).filter(row => 
            row.querySelector('input').value.trim() !== ''
        ).length;

        if (!priceAfterDegradation) return 0;

        let reductionRate;
        if (numberOfCompetitors <= 2) {
            reductionRate = 0.03; // 3% reduction
        } else if (numberOfCompetitors <= 5) {
            reductionRate = 0.06; // 6% reduction
        } else if (numberOfCompetitors > 5) {
            reductionRate = 0.10; // 10% reduction
        } else {
            reductionRate = 0;
        }

        return priceAfterDegradation - (priceAfterDegradation * reductionRate);
    }

    // Calculate local preference adjustment
    calculateLocalPreference(competitorsPrice, localPreferencePercent, wasWinnerLocal) {
        if (wasWinnerLocal !== 'Yes' || !localPreferencePercent) {
            return 0; // No deduction if winner was not local
        }

        return competitorsPrice * (localPreferencePercent / 100);
    }

    // Calculate final bid to ministry
    calculateFinalBid(competitorsPrice, localPreferenceDeduction) {
        return competitorsPrice - localPreferenceDeduction;
    }

    // Calculate CIF without commission
    calculateCIFWithoutCommission(finalBid, clientMargin, clientExpenses) {
        const totalCommission = (clientMargin + clientExpenses) / 100;
        return finalBid - (finalBid * totalCommission);
    }

    // Calculate Ex Works price
    calculateExWorksPrice(cifWithoutCommission, freightPercent) {
        const freightCost = cifWithoutCommission * (freightPercent / 100);
        return cifWithoutCommission - freightCost;
    }

    // Calculate gross contribution percentage
    calculateGrossContribution(exWorksPrice, ibPP) {
        if (!ibPP) return 0;
        return ((exWorksPrice - ibPP) / ibPP) * 100;
    }

    // Predict profit based on gross contribution
    predictProfit(grossContribution) {
        if (grossContribution >= 11) {
            return { text: 'Profit', class: 'profit-positive' };
        } else if (grossContribution <= 10 && grossContribution > 0) {
            return { text: 'Low Profit', class: 'profit-neutral' };
        } else if (grossContribution <= 0 && grossContribution >= -10) {
            return { text: 'No Profit', class: 'profit-neutral' };
        } else if (grossContribution < -11) {
            return { text: 'Loss', class: 'profit-negative' };
        } else {
            return { text: 'Calculate', class: '' };
        }
    }

    // Calculate percentage reduction from last VRL CIF price
    calculateReductionFromLastPrice(lastVRLCIFPrice, cifWithoutCommission) {
        if (!lastVRLCIFPrice) return 0;
        return ((lastVRLCIFPrice - cifWithoutCommission) / lastVRLCIFPrice) * 100;
    }

    // Format currency values
    formatCurrency(value) {
        if (isNaN(value) || value === 0) return '$0.00';
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(Math.abs(value));
    }

    // Format percentage values
    formatPercentage(value) {
        if (isNaN(value)) return '0%';
        return `${value.toFixed(2)}%`;
    }

    // Update results display
    updateResults(calculations, values) {
        // Update main result cards
        document.getElementById('finalBid').textContent = this.formatCurrency(calculations.finalBid);
        document.getElementById('cifWithoutCommission').textContent = this.formatCurrency(calculations.cifWithoutCommission);
        document.getElementById('exWorksPrice').textContent = this.formatCurrency(calculations.exWorksPrice);
        document.getElementById('grossContribution').textContent = this.formatPercentage(calculations.grossContribution);
        document.getElementById('reductionPercent').textContent = this.formatPercentage(calculations.reductionPercent);

        // Update profit prediction with styling
        const profitElement = document.getElementById('profitPrediction');
        const profitPrediction = this.predictProfit(calculations.grossContribution);
        profitElement.textContent = profitPrediction.text;
        profitElement.className = `result-value ${profitPrediction.class}`;

        // Update detailed analysis table
        document.getElementById('analysisProduct').textContent = values.productName || '-';
        document.getElementById('yearDiff').textContent = calculations.yearsDiff || '-';
        document.getElementById('priceAfterDegradation').textContent = this.formatCurrency(calculations.priceAfterDegradation);
        document.getElementById('competitorsBenchmark').textContent = this.formatCurrency(calculations.competitorsPrice);
        document.getElementById('localPrefCalc').textContent = this.formatCurrency(calculations.localPreferenceDeduction);
        document.getElementById('finalBidAnalysis').textContent = this.formatCurrency(calculations.finalBid);
        document.getElementById('predictionsAnalysis').textContent = profitPrediction.text;
    }

    // Validate required fields
    validateInputs(values) {
        const errors = [];
        
        if (!values.productName) {
            errors.push('Product Name is required');
        }
        
        if (!values.lastVRLCIFPrice) {
            errors.push('Last VRL CIF Price is required for calculations');
        }
        
        if (!values.ibPP) {
            errors.push('IB PP is required for profit calculations');
        }

        return errors;
    }

    // Show validation errors
    showValidationErrors(errors) {
        if (errors.length > 0) {
            // You could implement a toast notification system here
            console.warn('Validation errors:', errors);
            return false;
        }
        return true;
    }

    // Main calculation function
    calculateBid() {
        const values = this.getFormValues();
        const validationErrors = this.validateInputs(values);
        
        // Show loading state
        const calculateBtn = document.querySelector('.btn-primary');
        if (calculateBtn) {
            calculateBtn.classList.add('loading');
            calculateBtn.disabled = true;
        }

        // Perform calculations
        try {
            const yearsDiff = this.calculateYearDifference(values.lastQuotedYear);
            const priceAfterDegradation = this.calculateYearlyDegradation(yearsDiff, values.lastVRLCIFPrice);
            const competitorsPrice = this.calculateCompetitorsBenchmarking(priceAfterDegradation);
            const localPreferenceDeduction = this.calculateLocalPreference(
                competitorsPrice, 
                values.localPreference, 
                values.wasWinnerLocal
            );
            const finalBid = this.calculateFinalBid(competitorsPrice, localPreferenceDeduction);
            const cifWithoutCommission = this.calculateCIFWithoutCommission(
                finalBid, 
                values.clientMargin, 
                values.clientExpenses
            );
            const exWorksPrice = this.calculateExWorksPrice(cifWithoutCommission, values.tentativeFreight);
            const grossContribution = this.calculateGrossContribution(exWorksPrice, values.ibPP);
            const reductionPercent = this.calculateReductionFromLastPrice(
                values.lastVRLCIFPrice, 
                cifWithoutCommission
            );

            const calculations = {
                yearsDiff,
                priceAfterDegradation,
                competitorsPrice,
                localPreferenceDeduction,
                finalBid,
                cifWithoutCommission,
                exWorksPrice,
                grossContribution,
                reductionPercent
            };

            // Store calculations for PDF export
            this.lastCalculations = calculations;

            // Update the display
            this.updateResults(calculations, values);

            // Add visual feedback for successful calculation
            this.showCalculationSuccess();

        } catch (error) {
            console.error('Calculation error:', error);
            this.showCalculationError();
        } finally {
            // Remove loading state
            setTimeout(() => {
                if (calculateBtn) {
                    calculateBtn.classList.remove('loading');
                    calculateBtn.disabled = false;
                }
            }, 500);
        }
    }

    // Show calculation success feedback
    showCalculationSuccess() {
        // Only show success message for major calculation completion
        const calculations = this.getLastCalculations();
        
        this.showStatusMessage(
            `✓ Analysis Complete! Final bid: €${calculations.finalBid.toFixed(2)}`,
            'success'
        );
    }
    
    // Get currency for department
    getCurrencyForDepartment(department) {
        return department === 'IB VPG Germany' ? 'EUR' : 'USD';
    }
    
    // Update IB Purchase Price label with currency
    updateIBPriceLabel() {
        const department = document.getElementById('department').value;
        const currency = this.getCurrencyForDepartment(department);
        const label = document.querySelector('label[for="ibPP"]');
        if (label) {
            label.textContent = `IB Purchase Price (${currency})`;
        }
    }
    
    // Update self-working fields with data from main form
    updateSelfWorkingFields() {
        const values = this.getFormValues();
        
        // Update readonly fields
        document.getElementById('swProduct').value = values.productName || '';
        document.getElementById('swLastWinningPrice').value = values.lastYearWinningPrize || '';
        document.getElementById('swClientMargin').value = values.clientMargin || '';
        document.getElementById('swClientExpenses').value = values.clientExpenses || '';
        document.getElementById('swFreight').value = values.tentativeFreight || '';
        
        // Update currency label
        this.updateIBPriceLabel();
        
        // Auto-calculate if reduction is entered
        const reductionPercent = parseFloat(document.getElementById('swReductionPercent').value) || 0;
        if (reductionPercent > 0) {
            this.calculateSelfWorking();
        }
    }
    
    // Calculate self-working (manual) results
    calculateSelfWorking() {
        const swValues = {
            lastWinningPrice: parseFloat(document.getElementById('swLastWinningPrice').value) || 0,
            reductionPercent: parseFloat(document.getElementById('swReductionPercent').value) || 0,
            clientMargin: parseFloat(document.getElementById('swClientMargin').value) || 0,
            clientExpenses: parseFloat(document.getElementById('swClientExpenses').value) || 0,
            freight: parseFloat(document.getElementById('swFreight').value) || 0,
            ibPP: parseFloat(document.getElementById('ibPP').value) || 0
        };
        
        // Calculate manual final bid (Last Winning Price - Reduction%)
        const reductionAmount = swValues.lastWinningPrice * (swValues.reductionPercent / 100);
        const manualFinalBid = swValues.lastWinningPrice - reductionAmount;
        
        // Calculate CIF without commission
        const totalCommission = (swValues.clientMargin + swValues.clientExpenses) / 100;
        const manualCIF = manualFinalBid - (manualFinalBid * totalCommission);
        
        // Calculate Ex Works price
        const freightCost = manualCIF * (swValues.freight / 100);
        const manualExWorks = manualCIF - freightCost;
        
        // Calculate gross contribution
        const manualGrossContribution = swValues.ibPP > 0 ? 
            ((manualExWorks - swValues.ibPP) / swValues.ibPP) * 100 : 0;
        
        // Calculate reduction from last price
        const manualReductionFromLast = swValues.lastWinningPrice > 0 ? 
            ((swValues.lastWinningPrice - manualCIF) / swValues.lastWinningPrice) * 100 : 0;
        
        // Store results
        this.selfWorkingResults = {
            finalBid: manualFinalBid,
            cifWithoutCommission: manualCIF,
            exWorksPrice: manualExWorks,
            grossContribution: manualGrossContribution,
            reductionFromLast: manualReductionFromLast
        };
        
        // Update display
        document.getElementById('swFinalBid').textContent = this.formatCurrency(manualFinalBid);
        document.getElementById('swCifWithoutCommission').textContent = this.formatCurrency(manualCIF);
        document.getElementById('swExWorksPrice').textContent = this.formatCurrency(manualExWorks);
        document.getElementById('swGrossContribution').textContent = this.formatPercentage(manualGrossContribution);
        document.getElementById('swReductionFromLast').textContent = this.formatPercentage(manualReductionFromLast);
        
        // Update profit prediction
        const profitPrediction = this.predictProfit(manualGrossContribution);
        const profitElement = document.getElementById('swProfitPrediction');
        profitElement.textContent = profitPrediction.text;
        profitElement.className = `result-value ${profitPrediction.class}`;
        
        this.showStatusMessage('✓ Manual calculation completed!', 'success');
    }
    
    // Generate comparison between auto and manual results
    generateComparison() {
        const autoResults = this.getLastCalculations();
        const manualResults = this.selfWorkingResults;
        
        if (!autoResults.finalBid || !manualResults.finalBid) {
            this.showStatusMessage('⚠ Please complete both auto and manual calculations first.', 'warning');
            return;
        }
        
        // Calculate differences and variances
        const comparisons = {
            finalBid: {
                auto: autoResults.finalBid,
                manual: manualResults.finalBid,
                diff: manualResults.finalBid - autoResults.finalBid,
                variance: autoResults.finalBid !== 0 ? ((manualResults.finalBid - autoResults.finalBid) / autoResults.finalBid) * 100 : 0
            },
            cif: {
                auto: autoResults.cifWithoutCommission,
                manual: manualResults.cifWithoutCommission,
                diff: manualResults.cifWithoutCommission - autoResults.cifWithoutCommission,
                variance: autoResults.cifWithoutCommission !== 0 ? ((manualResults.cifWithoutCommission - autoResults.cifWithoutCommission) / autoResults.cifWithoutCommission) * 100 : 0
            },
            exWorks: {
                auto: autoResults.exWorksPrice,
                manual: manualResults.exWorksPrice,
                diff: manualResults.exWorksPrice - autoResults.exWorksPrice,
                variance: autoResults.exWorksPrice !== 0 ? ((manualResults.exWorksPrice - autoResults.exWorksPrice) / autoResults.exWorksPrice) * 100 : 0
            },
            gross: {
                auto: autoResults.grossContribution,
                manual: manualResults.grossContribution,
                diff: manualResults.grossContribution - autoResults.grossContribution,
                variance: autoResults.grossContribution !== 0 ? ((manualResults.grossContribution - autoResults.grossContribution) / autoResults.grossContribution) * 100 : 0
            }
        };
        
        // Update comparison table with enhanced formatting
        this.updateComparisonCell('compAutoFinalBid', this.formatCurrency(comparisons.finalBid.auto));
        this.updateComparisonCell('compManualFinalBid', this.formatCurrency(comparisons.finalBid.manual));
        this.updateComparisonCell('compDiffFinalBid', this.formatCurrency(comparisons.finalBid.diff), comparisons.finalBid.diff);
        this.updateComparisonCell('compVarFinalBid', this.formatPercentage(comparisons.finalBid.variance), comparisons.finalBid.variance);
        
        this.updateComparisonCell('compAutoCIF', this.formatCurrency(comparisons.cif.auto));
        this.updateComparisonCell('compManualCIF', this.formatCurrency(comparisons.cif.manual));
        this.updateComparisonCell('compDiffCIF', this.formatCurrency(comparisons.cif.diff), comparisons.cif.diff);
        this.updateComparisonCell('compVarCIF', this.formatPercentage(comparisons.cif.variance), comparisons.cif.variance);
        
        this.updateComparisonCell('compAutoExWorks', this.formatCurrency(comparisons.exWorks.auto));
        this.updateComparisonCell('compManualExWorks', this.formatCurrency(comparisons.exWorks.manual));
        this.updateComparisonCell('compDiffExWorks', this.formatCurrency(comparisons.exWorks.diff), comparisons.exWorks.diff);
        this.updateComparisonCell('compVarExWorks', this.formatPercentage(comparisons.exWorks.variance), comparisons.exWorks.variance);
        
        this.updateComparisonCell('compAutoGross', this.formatPercentage(comparisons.gross.auto));
        this.updateComparisonCell('compManualGross', this.formatPercentage(comparisons.gross.manual));
        this.updateComparisonCell('compDiffGross', this.formatPercentage(comparisons.gross.diff), comparisons.gross.diff);
        this.updateComparisonCell('compVarGross', this.formatPercentage(comparisons.gross.variance), comparisons.gross.variance);
        
        // Profit predictions
        const autoProfit = this.predictProfit(autoResults.grossContribution);
        const manualProfit = this.predictProfit(manualResults.grossContribution);
        document.getElementById('compAutoProfit').textContent = autoProfit.text;
        document.getElementById('compManualProfit').textContent = manualProfit.text;
        document.getElementById('compDiffProfit').textContent = autoProfit.text === manualProfit.text ? 'Same' : 'Different';
        document.getElementById('compVarProfit').textContent = '-';
        
        // Generate summary
        const avgVariance = Math.abs((comparisons.finalBid.variance + comparisons.cif.variance + comparisons.exWorks.variance + comparisons.gross.variance) / 4);
        let summaryText = '';
        
        if (avgVariance < 5) {
            summaryText = '✅ Excellent alignment! Auto and manual calculations are very close (avg variance < 5%).';
        } else if (avgVariance < 15) {
            summaryText = '⚠️ Moderate variance detected. Review calculation parameters (avg variance < 15%).';
        } else {
            summaryText = '❌ Significant variance found! Manual and auto calculations differ substantially (avg variance > 15%).';
        }
        
        document.getElementById('summaryText').textContent = summaryText;
        document.getElementById('comparisonSummary').style.display = 'block';
        
        this.showStatusMessage('✓ Comparison analysis completed!', 'success');
    }
    
    // Update comparison cell with appropriate styling
    updateComparisonCell(elementId, text, value = null) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        element.textContent = text;
        
        // Remove existing classes
        element.classList.remove('positive-variance', 'negative-variance', 'neutral-variance');
        
        // Apply styling based on value for difference and variance cells
        if (value !== null && (elementId.includes('Diff') || elementId.includes('Var'))) {
            if (Math.abs(value) < 0.01) {
                element.classList.add('neutral-variance');
            } else if (value > 0) {
                element.classList.add('positive-variance');
            } else {
                element.classList.add('negative-variance');
            }
        }
    }
    
    // Show status messages with better context
    showStatusMessage(message, type = 'info') {
        // Remove existing status message
        const existingMessage = document.getElementById('status-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new status message
        const statusMessage = document.createElement('div');
        statusMessage.id = 'status-message';
        statusMessage.textContent = message;
        
        const colors = {
            success: { bg: '#10b981', border: '#059669' },
            warning: { bg: '#f59e0b', border: '#d97706' },
            error: { bg: '#ef4444', border: '#dc2626' },
            info: { bg: '#3b82f6', border: '#2563eb' }
        };
        
        const color = colors[type] || colors.info;
        
        statusMessage.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${color.bg};
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-width: 400px;
            border-left: 4px solid ${color.border};
        `;
        
        document.body.appendChild(statusMessage);
        
        // Remove after 2.5 seconds (faster)
        setTimeout(() => {
            if (statusMessage.parentNode) {
                statusMessage.style.opacity = '0';
                statusMessage.style.transform = 'translateX(100%)';
                statusMessage.style.transition = 'all 0.3s ease';
                setTimeout(() => statusMessage.remove(), 300);
            }
        }, 2500);
    }

    // Show calculation error feedback
    showCalculationError() {
        // You could implement error notification here
        console.error('Failed to calculate bid');
    }

    // Export results to CSV
    exportResults() {
        const values = this.getFormValues();
        const calculations = this.getLastCalculations();
        
        const csvData = [
            ['BidCraft Analysis Results'],
            ['Generated on:', new Date().toLocaleDateString()],
            [''],
            ['Product Information'],
            ['Product Name:', values.productName],
            ['Grade:', values.grade],
            ['Department:', values.department],
            ['Registration:', values.registration],
            ['Country:', values.country],
            ['Last Quoted Year:', values.lastQuotedYear],
            ['Tender Quantity:', values.tenderQuantity],
            ['Supply Remarks:', values.supplyRemarks || 'None provided'],
            [''],
            ['Auto-Calculated Results'],
            ['Auto Final Bid:', calculations.finalBid],
            ['Auto CIF Without Commission:', calculations.cifWithoutCommission],
            ['Auto Ex Works Price:', calculations.exWorksPrice],
            ['Auto Gross Contribution (%):', calculations.grossContribution],
            [''],
            ['Manual (Self-Working) Results'],
            ['Manual Final Bid:', this.selfWorkingResults.finalBid || 'Not calculated'],
            ['Manual CIF Without Commission:', this.selfWorkingResults.cifWithoutCommission || 'Not calculated'],
            ['Manual Ex Works Price:', this.selfWorkingResults.exWorksPrice || 'Not calculated'],
            ['Manual Gross Contribution (%):', this.selfWorkingResults.grossContribution || 'Not calculated'],
            [''],
            ['Financial Results'],
            ['Final Bid to Ministry:', calculations.finalBid],
            ['CIF Without Commission:', calculations.cifWithoutCommission],
            ['Ex Works Price:', calculations.exWorksPrice],
            ['Gross Contribution (%):', calculations.grossContribution],
            ['Profit Prediction:', this.predictProfit(calculations.grossContribution).text],
            ['% Reduction from Last Price:', calculations.reductionPercent],
        ];

        this.downloadCSV(csvData, 'bidcraft-analysis-results.csv');
    }

    // Generate PDF Report
    generatePDFReport() {
        const values = this.getFormValues();
        const calculations = this.getLastCalculations();
        const profitPrediction = this.predictProfit(calculations.grossContribution);
        
        const currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        return `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>BIDCRAFT NEURAL NETWORK - ANALYSIS REPORT - ${values.productName || 'PRODUCT'}</title>
            <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Source+Code+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style>
                :root {
                    --matrix-green: #00ff00;
                    --matrix-dark-green: #006600;
                    --matrix-darker-green: #004400;
                    --matrix-light-green: #0dff0d;
                    --matrix-neon-green: #39ff14;
                    --matrix-black: #000000;
                    --matrix-dark-gray: #0a0a0a;
                    --matrix-medium-gray: #1a1a1a;
                    --matrix-border: #00ff0030;
                    --matrix-glow: #00ff0050;
                }
                
                body {
                    font-family: 'Source Code Pro', 'Orbitron', monospace;
                    line-height: 1.6;
                    color: var(--matrix-green);
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 40px;
                    background: var(--matrix-black);
                }
                
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    padding: 30px;
                    background: linear-gradient(135deg, var(--matrix-dark-gray) 0%, var(--matrix-medium-gray) 100%);
                    color: var(--matrix-green);
                    border-radius: 12px;
                    box-shadow: 0 0 20px var(--matrix-glow);
                    border: 2px solid var(--matrix-border);
                    position: relative;
                    overflow: hidden;
                }
                
                .header::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 4px;
                    background: linear-gradient(90deg, var(--matrix-dark-green), var(--matrix-green), var(--matrix-light-green));
                }
                
                .header h1 {
                    margin: 0;
                    font-size: 2.5rem;
                    font-weight: 900;
                    font-family: 'Orbitron', monospace;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                    text-shadow: 0 0 10px var(--matrix-green);
                }
                
                .header p {
                    margin: 10px 0 0 0;
                    font-size: 1.2rem;
                    opacity: 0.9;
                    font-family: 'Source Code Pro', monospace;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                
                .report-meta {
                    background: linear-gradient(135deg, var(--matrix-dark-gray), var(--matrix-medium-gray));
                    padding: 25px;
                    border-radius: 12px;
                    border: 1px solid var(--matrix-border);
                    margin-bottom: 25px;
                    border-left: 4px solid var(--matrix-green);
                    box-shadow: 0 0 15px var(--matrix-glow);
                }
                
                .section {
                    background: linear-gradient(135deg, var(--matrix-dark-gray), var(--matrix-medium-gray));
                    margin-bottom: 25px;
                    border-radius: 12px;
                    border: 1px solid var(--matrix-border);
                    overflow: hidden;
                    box-shadow: 0 0 15px var(--matrix-glow);
                }
                
                .section-header {
                    background: linear-gradient(135deg, var(--matrix-dark-green), var(--matrix-darker-green));
                    color: var(--matrix-light-green);
                    padding: 20px 25px;
                    font-size: 1.3rem;
                    font-weight: 700;
                    border-bottom: 1px solid var(--matrix-border);
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    font-family: 'Orbitron', monospace;
                    text-shadow: 0 0 5px var(--matrix-green);
                }
                
                .section-content {
                    padding: 25px;
                    color: var(--matrix-green);
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 20px;
                    margin-bottom: 20px;
                }
                
                .info-item {
                    display: flex;
                    flex-direction: column;
                }
                
                .info-label {
                    font-size: 0.9rem;
                    color: rgba(0, 255, 0, 0.7);
                    font-weight: 500;
                    margin-bottom: 5px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-family: 'Source Code Pro', monospace;
                }
                
                .info-value {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: var(--matrix-green);
                    font-family: 'Source Code Pro', monospace;
                }
                
                .results-highlight {
                    background: linear-gradient(135deg, var(--matrix-dark-green) 0%, var(--matrix-darker-green) 100%);
                    color: var(--matrix-light-green);
                    padding: 30px;
                    border-radius: 12px;
                    text-align: center;
                    margin: 30px 0;
                    box-shadow: 0 0 25px var(--matrix-green);
                    border: 2px solid var(--matrix-green);
                }
                
                .final-bid {
                    font-size: 2.5rem;
                    font-weight: 700;
                    margin: 15px 0;
                    text-shadow: 0 0 10px var(--matrix-green);
                    font-family: 'Orbitron', monospace;
                    color: var(--matrix-neon-green);
                }
                
                .profit-indicator {
                    font-size: 1.3rem;
                    font-weight: 600;
                    padding: 10px 20px;
                    border-radius: 8px;
                    display: inline-block;
                    margin-top: 15px;
                    font-family: 'Source Code Pro', monospace;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                }
                
                .profit-positive { background: rgba(0, 255, 0, 0.2); color: var(--matrix-neon-green); border: 1px solid var(--matrix-green); }
                .profit-negative { background: rgba(255, 0, 0, 0.2); color: #ff4444; border: 1px solid #ff4444; }
                .profit-neutral { background: rgba(255, 255, 0, 0.2); color: #ffff00; border: 1px solid #ffff00; }
                
                .analysis-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    font-family: 'Source Code Pro', monospace;
                }
                
                .analysis-table th {
                    background: linear-gradient(135deg, var(--matrix-dark-green), var(--matrix-darker-green));
                    color: var(--matrix-light-green);
                    padding: 15px;
                    text-align: left;
                    font-weight: 700;
                    border-bottom: 2px solid var(--matrix-border);
                    text-transform: uppercase;
                    font-size: 0.8rem;
                    letter-spacing: 0.5px;
                    text-shadow: 0 0 5px var(--matrix-green);
                }
                
                .analysis-table td {
                    padding: 12px 15px;
                    border-bottom: 1px solid var(--matrix-border);
                    color: var(--matrix-green);
                    background: var(--matrix-dark-gray);
                }
                
                .footer {
                    text-align: center;
                    margin-top: 40px;
                    padding: 25px;
                    background: linear-gradient(135deg, var(--matrix-dark-gray), var(--matrix-medium-gray));
                    border-radius: 12px;
                    border: 1px solid var(--matrix-border);
                    color: var(--matrix-green);
                    font-size: 0.9rem;
                    box-shadow: 0 0 15px var(--matrix-glow);
                    font-family: 'Source Code Pro', monospace;
                }
                
                @media print {
                    body { padding: 20px; background: var(--matrix-black) !important; }
                    .section { page-break-inside: avoid; }
                    * { color: var(--matrix-green) !important; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>⚡ BIDCRAFT NEURAL NETWORK ⚡</h1>
                <p>ANALYSIS REPORT - TENDER INTELLIGENCE MATRIX</p>
            </div>
            
            <div class="report-meta">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Report Generated</div>
                        <div class="info-value">${currentDate}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Product</div>
                        <div class="info-value">${values.productName || 'Not specified'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Grade</div>
                        <div class="info-value">${values.grade || 'Not specified'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Department</div>
                        <div class="info-value">${values.department || 'Not specified'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Target Country</div>
                        <div class="info-value">${values.country || 'Not specified'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Registration Status</div>
                        <div class="info-value">${values.registration || 'Not specified'}</div>
                    </div>
                </div>
            </div>
            
            <div class="results-highlight">
                <h2>⚡ NEURAL NETWORK BID CALCULATION ⚡</h2>
                <div class="final-bid">${this.formatCurrency(calculations.finalBid || 0)}</div>
                <div class="profit-indicator ${profitPrediction.class}">
                    PROFIT_PREDICTION.EXE: ${profitPrediction.text}
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">📊 FINANCIAL_ANALYSIS_MATRIX.EXE</div>
                <div class="section-content">
                    <table class="analysis-table">
                        <tr>
                            <th>Metric</th>
                            <th>Value</th>
                            <th>Description</th>
                        </tr>
                        <tr>
                            <td>Final Bid to Ministry</td>
                            <td><strong>${this.formatCurrency(calculations.finalBid || 0)}</strong></td>
                            <td>Recommended bidding price after all adjustments</td>
                        </tr>
                        <tr>
                            <td>CIF Without Commission</td>
                            <td>${this.formatCurrency(calculations.cifWithoutCommission || 0)}</td>
                            <td>Price after removing client margins and expenses</td>
                        </tr>
                        <tr>
                            <td>Ex Works Price</td>
                            <td>${this.formatCurrency(calculations.exWorksPrice || 0)}</td>
                            <td>Manufacturing price after freight deduction</td>
                        </tr>
                        <tr>
                            <td>Gross Contribution</td>
                            <td><strong>${this.formatPercentage(calculations.grossContribution || 0)}</strong></td>
                            <td>Profit margin based on IB Purchase Price</td>
                        </tr>
                        <tr>
                            <td>Price Reduction from Last Quote</td>
                            <td>${this.formatPercentage(calculations.reductionPercent || 0)}</td>
                            <td>Competitive pricing adjustment</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">🔍 CALCULATION_BREAKDOWN.EXE</div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Years Since Last Quote</div>
                            <div class="info-value">${calculations.yearsDiff || 0} years</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Price After Yearly Degradation</div>
                            <div class="info-value">${this.formatCurrency(calculations.priceAfterDegradation || 0)}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Competitors Benchmarking</div>
                            <div class="info-value">${this.formatCurrency(calculations.competitorsPrice || 0)}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Local Preference Adjustment</div>
                            <div class="info-value">${this.formatCurrency(calculations.localPreferenceDeduction || 0)}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">💼 INPUT_PARAMETERS.EXE</div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Client Margin</div>
                            <div class="info-value">${values.clientMargin}%</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Client Expenses</div>
                            <div class="info-value">${values.clientExpenses}%</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tentative Freight</div>
                            <div class="info-value">${values.tentativeFreight}%</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">IB Purchase Price</div>
                            <div class="info-value">${this.formatCurrency(values.ibPP)}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Last VRL CIF Price</div>
                            <div class="info-value">${this.formatCurrency(values.lastVRLCIFPrice)}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Last Year Winning Prize</div>
                            <div class="info-value">${this.formatCurrency(values.lastYearWinningPrize)}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            ${this.selfWorkingResults.finalBid ? `
            <div class="section">
                <div class="section-header">⚖️ AUTO_VS_MANUAL_COMPARISON.EXE</div>
                <div class="section-content">
                    <table class="analysis-table">
                        <tr>
                            <th>Metric</th>
                            <th>Auto-Calculated</th>
                            <th>Manual (Self-Working)</th>
                            <th>Variance %</th>
                        </tr>
                        <tr>
                            <td>Final Bid to Ministry</td>
                            <td><strong>${this.formatCurrency(calculations.finalBid || 0)}</strong></td>
                            <td><strong>${this.formatCurrency(this.selfWorkingResults.finalBid)}</strong></td>
                            <td>${this.formatPercentage(calculations.finalBid !== 0 ? ((this.selfWorkingResults.finalBid - calculations.finalBid) / calculations.finalBid) * 100 : 0)}</td>
                        </tr>
                        <tr>
                            <td>CIF Without Commission</td>
                            <td>${this.formatCurrency(calculations.cifWithoutCommission || 0)}</td>
                            <td>${this.formatCurrency(this.selfWorkingResults.cifWithoutCommission)}</td>
                            <td>${this.formatPercentage(calculations.cifWithoutCommission !== 0 ? ((this.selfWorkingResults.cifWithoutCommission - calculations.cifWithoutCommission) / calculations.cifWithoutCommission) * 100 : 0)}</td>
                        </tr>
                        <tr>
                            <td>Ex Works Price</td>
                            <td>${this.formatCurrency(calculations.exWorksPrice || 0)}</td>
                            <td>${this.formatCurrency(this.selfWorkingResults.exWorksPrice)}</td>
                            <td>${this.formatPercentage(calculations.exWorksPrice !== 0 ? ((this.selfWorkingResults.exWorksPrice - calculations.exWorksPrice) / calculations.exWorksPrice) * 100 : 0)}</td>
                        </tr>
                        <tr>
                            <td>Gross Contribution (%)</td>
                            <td>${this.formatPercentage(calculations.grossContribution || 0)}</td>
                            <td>${this.formatPercentage(this.selfWorkingResults.grossContribution)}</td>
                            <td>${this.formatPercentage(calculations.grossContribution !== 0 ? ((this.selfWorkingResults.grossContribution - calculations.grossContribution) / calculations.grossContribution) * 100 : 0)}</td>
                        </tr>
                        <tr>
                            <td>Profit Prediction</td>
                            <td>${this.predictProfit(calculations.grossContribution).text}</td>
                            <td>${this.predictProfit(this.selfWorkingResults.grossContribution).text}</td>
                            <td>-</td>
                        </tr>
                    </table>
                    
                    <div style="margin-top: 20px; padding: 15px; background: rgba(0, 255, 0, 0.05); border-radius: 8px; border-left: 4px solid var(--matrix-green);">
                        <h4>📊 NEURAL_COMPARISON_ANALYSIS.EXE</h4>
                        <p><strong>METHODOLOGY:</strong> This comparison matrix shows divergence patterns between BIDCRAFT's automated neural algorithms (quantum market data processing) versus manual human calculations (organic intelligence input).</p>
                        <p><strong>VARIANCE_ANALYSIS:</strong> Significant divergence patterns may indicate alternate market assumptions, risk assessment protocols, or strategic consideration matrices between automated neural networks and human cognitive processing.</p>
                    </div>
                </div>
            </div>
            ` : ''}
            
            <div class="footer">
                <p><strong>BIDCRAFT NEURAL NETWORK - GLOBAL TENDER INTELLIGENCE MATRIX</strong></p>
                <p>SYSTEM://GENERATED_FOR_VENUS_REMEDIES_LTD.EXE</p>
                <p>WARNING: CLASSIFIED PRICING ANALYSIS - HANDLE WITH MAXIMUM SECURITY PROTOCOLS</p>
            </div>
        </body>
        </html>
        `;
    }

    // Get last calculations (store in class property)
    getLastCalculations() {
        return this.lastCalculations || {
            yearsDiff: 0,
            priceAfterDegradation: 0,
            competitorsPrice: 0,
            localPreferenceDeduction: 0,
            finalBid: 0,
            cifWithoutCommission: 0,
            exWorksPrice: 0,
            grossContribution: 0,
            reductionPercent: 0
        };
    }

    // Download CSV helper function
    downloadCSV(data, filename) {
        const csvContent = data.map(row => row.join(',')).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', filename);
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
}

// Table management functions - removed addTenderRow since Recent Tender Information section was removed

function addCompetitorRow() {
    const tbody = document.getElementById('competitorsTableBody');
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td><input type="text" class="table-input" placeholder="Company name"></td>
        <td><input type="text" class="table-input" placeholder="Country"></td>
        <td><input type="text" class="table-input" placeholder="Marketing company"></td>
        <td><input type="text" class="table-input" placeholder="Country"></td>
        <td><input type="text" class="table-input" placeholder="Agent name"></td>
        <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
    `;
    addInputEventListeners(newRow);
}

function addExportRow() {
    const tbody = document.getElementById('exportTableBody');
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td><input type="text" class="table-input" placeholder="Company name"></td>
        <td><input type="text" class="table-input" placeholder="Country"></td>
        <td><input type="number" class="table-input" placeholder="0"></td>
        <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
        <td><input type="text" class="table-input" placeholder="Competitors"></td>
    `;
    addInputEventListeners(newRow);
}

function addOtherTenderRow() {
    const tbody = document.getElementById('otherTendersTableBody');
    const newRow = tbody.insertRow();
    newRow.innerHTML = `
        <td><input type="text" class="table-input" placeholder="Tender name"></td>
        <td><input type="text" class="table-input" placeholder="Product name"></td>
        <td><input type="number" class="table-input" placeholder="0"></td>
        <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
        <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
        <td><input type="text" class="table-input" placeholder="Awardee name"></td>
        <td><input type="number" class="table-input" placeholder="0.00" step="0.01"></td>
        <td><input type="text" class="table-input" placeholder="Awardee name"></td>
    `;
    addInputEventListeners(newRow);
}

// Add event listeners to new input elements
function addInputEventListeners(row) {
    const inputs = row.querySelectorAll('.table-input');
    inputs.forEach(input => {
        input.addEventListener('input', () => bidCraft.debounce(bidCraft.calculateBid.bind(bidCraft), 500)());
        input.addEventListener('change', () => bidCraft.calculateBid());
    });
}

// Initialize the application with GSAP ready check
let bidCraft;
document.addEventListener('DOMContentLoaded', () => {
    // Ensure GSAP is loaded
    if (typeof gsap === 'undefined') {
        console.warn('GSAP not loaded, falling back to basic animations');
    }
    
    bidCraft = new BidCraft();
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl+E to export results
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            bidCraft.exportResults();
        }
        
        // Ctrl+R to recalculate
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            bidCraft.calculateBid();
        }
    });
    
    // Add helpful tooltips and guides
    addTooltips();
});

// Add tooltips for better user experience
function addTooltips() {
    const tooltips = {
        'productName': 'Select the product you want to bid for',
        'registration': 'Product registration status in the target country',
        'lastQuotedYear': 'The most recent year this product was quoted',
        'clientMargin': 'Percentage margin the client expects',
        'clientExpenses': 'Additional expenses as a percentage',
        'tentativeFreight': 'Estimated freight cost as a percentage',
        'localPreference': 'Local preference percentage (applies only if last winner was local)',
        'lastVRLCIFPrice': 'Last VRL CIF price quoted to the client',
        'wasWinnerLocal': 'Whether the previous tender winner was a local company'
    };
    
    Object.entries(tooltips).forEach(([id, text]) => {
        const element = document.getElementById(id);
        if (element) {
            element.title = text;
            element.addEventListener('focus', () => {
                // You could show a more sophisticated tooltip here
            });
        }
    });
}

// Global function for the calculate button
function calculateBid() {
    if (bidCraft) {
        bidCraft.calculateBid();
    }
}

// Global function for self-working calculation
function calculateSelfWorking() {
    if (bidCraft) {
        bidCraft.calculateSelfWorking();
    }
}

// Global function for comparison generation
function generateComparison() {
    if (bidCraft) {
        bidCraft.generateComparison();
    }
}


// Global function for PDF export
function exportToPDF() {
    if (bidCraft) {
        // Show loading state
        const exportBtn = document.querySelector('.export-btn');
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
        exportBtn.disabled = true;
        
        setTimeout(() => {
            const htmlReport = bidCraft.generatePDFReport();
            const blob = new Blob([htmlReport], { type: 'text/html' });
            const url = window.URL.createObjectURL(blob);
            
            // Open in new window for printing/saving as PDF  
            const printWindow = window.open(url, '_blank', 'width=800,height=600');
            if (printWindow) {
                printWindow.document.title = `BidCraft Analysis Report - ${new Date().toLocaleDateString()}`;
                printWindow.onload = function() {
                    setTimeout(() => {
                        printWindow.print();
                    }, 1000);
                };
            }
            
            // Reset button state
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
            
            // Clean up
            setTimeout(() => {
                window.URL.revokeObjectURL(url);
            }, 2000);
        }, 500);
    }
}
