<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$functions = <<<JS
                addHardSkill() {
                    if (this.newHardSkill.trim() !== '') {
                        this.data.skills.push({ name: this.newHardSkill.trim(), level: this.newHardSkillLevel });
                        this.newHardSkill = '';
                        this.updatePreview();
                    }
                },
                addSoftSkill() {
                    if (this.newSoftSkill.trim() !== '') {
                        this.data.skills.push({ name: this.newSoftSkill.trim(), level: null });
                        this.newSoftSkill = '';
                        this.updatePreview();
                    }
                },
                addTool() {
JS;

$content = str_replace("addTool() {", $functions, $content);

file_put_contents($file, $content);
echo "Added addHardSkill and addSoftSkill methods.\n";
