

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fieldVisibility', (fieldCode, visConfig) => ({
        visible: true,
        init() {
            this.checkVisibility();
            // Watch for data changes
            this.$watch('$wire.data', () => this.checkVisibility());
        },
        checkVisibility() {
            let data = this.$wire.data || {};
            let show = true;

            // Уровень A: привязка к покрытиям
            if (visConfig.coverage_codes && visConfig.coverage_codes.length > 0) {
                let anyActive = false;
                for (let code of visConfig.coverage_codes) {
                    let val = data[code];
                    let type = (visConfig.coverage_types && visConfig.coverage_types[code]) || 'range';

                    if (type === 'flag') {
                        if (val === true || val === 1 || val === '1' || val === 'on') {
                            anyActive = true;
                            break;
                        }
                    } else {
                        if (val !== null && val !== undefined && val !== '' && parseFloat(val) > 0) {
                            anyActive = true;
                            break;
                        }
                    }
                }
                if (!anyActive) show = false;
            }

            // Уровень B: JSON-условия
            if (show && visConfig.condition) {
                let cond = visConfig.condition;
                let logic = cond.logic || 'and';
                let conditions = cond.conditions || [];

                if (conditions.length > 0) {
                    if (logic === 'and') {
                        for (let c of conditions) {
                            if (!this.evalCondition(c, data)) { show = false; break; }
                        }
                    } else {
                        show = false;
                        for (let c of conditions) {
                            if (this.evalCondition(c, data)) { show = true; break; }
                        }
                    }
                }
            }

            this.visible = show;
        },
        evalCondition(cond, data) {
            let actual = data[cond.field_code];
            let expected = cond.value;
            let op = cond.operator || '=';

            switch(op) {
                case '=':  return actual == expected;
                case '!=': return actual != expected;
                case '>':  return parseFloat(actual) > parseFloat(expected);
                case '<':  return parseFloat(actual) < parseFloat(expected);
                case '>=': return parseFloat(actual) >= parseFloat(expected);
                case '<=': return parseFloat(actual) <= parseFloat(expected);
                case 'in': return Array.isArray(expected) ? expected.includes(actual) : actual == expected;
                case 'not_empty': return actual !== null && actual !== undefined && actual !== '' && actual !== 0;
                case 'empty': return actual === null || actual === undefined || actual === '' || actual === 0;
                default: return true;
            }
        }
    }));
});
</script>
<?php /**PATH A:\XAMPP\htdocs\insure-lk\resources\views/livewire/policies/partials/alpine-visibility.blade.php ENDPATH**/ ?>