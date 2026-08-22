<?php
$files = [
    'resources/views/cv/templates/creative.blade.php',
    'resources/views/cv/templates/fresh-graduate.blade.php',
    'resources/views/cv/templates/job-application.blade.php',
    'resources/views/cv/templates/student.blade.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // First, add the PHP filtering block at the top if it doesn't exist
    if (strpos($content, '$hard_skills') === false) {
        $content = str_replace(
            "<?php\n        \$data =", 
            "<?php\n        \$hard_skills = collect(\$userData['cv']['skills'] ?? [])->filter(fn(\$s) => isset(\$s['level']) && \$s['level'] !== '')->map(fn(\$s) => (object)\$s)->all();\n        \$soft_skills = collect(\$userData['cv']['skills'] ?? [])->filter(fn(\$s) => !isset(\$s['level']) || \$s['level'] === '')->map(fn(\$s) => (object)\$s)->all();\n        \$data =", 
            $content
        );
        // Wait, the block is @php \n $data = ...
        $content = str_replace(
            "@php\n        \$data =", 
            "@php\n        \$hard_skills = collect(isset(\$userData['cv']['skills']) && is_array(\$userData['cv']['skills']) ? \$userData['cv']['skills'] : [])->filter(fn(\$s) => isset(\$s['level']) && \$s['level'] !== '')->map(fn(\$s) => (object)\$s)->all();\n        \$soft_skills = collect(isset(\$userData['cv']['skills']) && is_array(\$userData['cv']['skills']) ? \$userData['cv']['skills'] : [])->filter(fn(\$s) => !isset(\$s['level']) || \$s['level'] === '')->map(fn(\$s) => (object)\$s)->all();\n        \$data =", 
            $content
        );
    }
    
    // Replace the old HTML logic
    $old_skills_block = '/@if\(count\(\$skills\) > 0\).*?<div class="right-header">Kemampuan.*?@endif/s';
    
    $new_skills_block = <<<HTML
                  @if(count(\$hard_skills) > 0)
                  <div class="right-header">Hard Skills</div>
                  <div style="padding-left: 0; margin-bottom: 20px;">
                      @foreach(\$hard_skills as \$skill)
                      <div style="margin-bottom: 10px;">
                          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 3px;">
                              <tr>
                                  <td style="font-size: 10pt; font-weight: bold; color: #111827;">{{ \$skill->name }}</td>
                                  <td align="right" style="font-size: 9pt; color: #D4AF37; font-weight: bold;">{{ \$skill->level }}%</td>
                              </tr>
                          </table>
                          <div style="width: 100%; background-color: #f3f4f6; height: 5px; border-radius: 3px;">
                              <div style="width: {{ \$skill->level }}%; background-color: #D4AF37; height: 100%; border-radius: 3px;"></div>
                          </div>
                      </div>
                      @endforeach
                  </div>
                  @endif

                  @if(count(\$soft_skills) > 0)
                  <div class="right-header">Soft Skills</div>
                  <ul class="skill-list" style="padding-left: 20px; margin-bottom: 20px;">
                      @foreach(\$soft_skills as \$skill)
                      <li>{{ \$skill->name }}</li>
                      @endforeach
                  </ul>
                  @endif
HTML;

    $content = preg_replace($old_skills_block, $new_skills_block, $content);
    file_put_contents($file, $content);
}
echo "Done separating skills in blade templates.\n";
