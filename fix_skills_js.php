<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$old_js = <<<JS
                addSkill() {
                    if (this.newSkill.trim() !== '') {
                        this.data.skills.push({ 
                            name: this.newSkill.trim(), 
                            level: this.newSkillLevel === '' ? null : this.newSkillLevel 
                        });
                        this.newSkill = '';
                        this.newSkillLevel = '80';
                        this.updatePreview();
                    }
                },
JS;

$new_js = <<<JS
                addHardSkill() {
                    if (this.newHardSkill.trim() !== '') {
                        this.data.skills.push({ 
                            name: this.newHardSkill.trim(), 
                            level: this.newHardSkillLevel
                        });
                        this.newHardSkill = '';
                        this.newHardSkillLevel = '80';
                        this.updatePreview();
                    }
                },
                addSoftSkill() {
                    if (this.newSoftSkill.trim() !== '') {
                        this.data.skills.push({ 
                            name: this.newSoftSkill.trim(), 
                            level: null
                        });
                        this.newSoftSkill = '';
                        this.updatePreview();
                    }
                },
JS;

$content = str_replace($old_js, $new_js, $content);

// Also replace the data variables if not already done correctly
$content = str_replace("newSkill: '',", "newHardSkill: '', newSoftSkill: '',", $content);
$content = str_replace("newSkillLevel: '80',", "newHardSkillLevel: '80',", $content);

file_put_contents($file, $content);
echo "Replaced JS\n";
