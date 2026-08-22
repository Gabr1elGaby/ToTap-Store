<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$old_html = '                        <label class="block text-xs font-bold text-gray-700 mb-2">Technical & Soft Skills</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(skill, index) in data.skills" :key="index">
                                <span class="bg-gray-100 border border-gray-200 px-3 py-1 rounded-full text-xs flex items-center gap-2">
                                    <span x-text="skill.name"></span>
                                    <span x-show="skill.level" class="text-blue-500 font-bold text-[10px]" x-text="skill.level + \'%\'"></span>
                                    <button @click="data.skills.splice(index, 1); updatePreview()" class="text-gray-400 hover:text-red-500">&times;</button>
                                </span>
                            </template>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" x-model="newSkill" @keydown.enter.prevent="addSkill" placeholder="Ketik skill (cth: SEO)" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <select x-model="newSkillLevel" class="border border-gray-300 rounded px-2 text-sm">
                                <option value="100">Pakar (100%)</option>
                                <option value="80">Mahir (80%)</option>
                                <option value="60">Menengah (60%)</option>
                                <option value="40">Dasar (40%)</option>
                                <option value="20">Pemula (20%)</option>
                                <option value="">Tanpa Level (Soft Skill)</option>
                            </select>
                            <button @click.prevent="addSkill" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-700">Tambah</button>
                        </div>';

$new_html = '                        <label class="block text-xs font-bold text-gray-700 mb-2 mt-4">Hard Skills (Technical)</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(skill, index) in data.skills" :key="\'h\'+index">
                                <span x-show="skill.level" class="bg-blue-100 text-blue-800 border border-blue-200 px-3 py-1 rounded-full text-xs flex items-center gap-2">
                                    <span x-text="skill.name"></span>
                                    <span class="font-bold text-[10px]" x-text="skill.level + \'%\'"></span>
                                    <button @click="data.skills.splice(index, 1); updatePreview()" class="text-blue-400 hover:text-red-500">&times;</button>
                                </span>
                            </template>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 mb-6">
                            <input type="text" x-model="newHardSkill" @keydown.enter.prevent="addHardSkill" placeholder="Ketik hard skill (cth: SEO)" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <select x-model="newHardSkillLevel" class="border border-gray-300 rounded px-3 py-2 text-sm shrink-0 w-full sm:w-auto">
                                <option value="100">Pakar (100%)</option>
                                <option value="80">Mahir (80%)</option>
                                <option value="60">Menengah (60%)</option>
                                <option value="40">Dasar (40%)</option>
                                <option value="20">Pemula (20%)</option>
                            </select>
                            <button @click.prevent="addHardSkill" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-700 shrink-0 w-full sm:w-auto whitespace-nowrap">Tambah</button>
                        </div>

                        <label class="block text-xs font-bold text-gray-700 mb-2">Soft Skills (Tanpa Level)</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(skill, index) in data.skills" :key="\'s\'+index">
                                <span x-show="!skill.level" class="bg-green-100 text-green-800 border border-green-200 px-3 py-1 rounded-full text-xs flex items-center gap-2">
                                    <span x-text="skill.name"></span>
                                    <button @click="data.skills.splice(index, 1); updatePreview()" class="text-green-400 hover:text-red-500">&times;</button>
                                </span>
                            </template>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" x-model="newSoftSkill" @keydown.enter.prevent="addSoftSkill" placeholder="Ketik soft skill (cth: Leadership)" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button @click.prevent="addSoftSkill" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-700 shrink-0 w-full sm:w-auto whitespace-nowrap">Tambah</button>
                        </div>';

$content = str_replace($old_html, $new_html, $content);
file_put_contents($file, $content);
echo "Replaced HTML\n";
