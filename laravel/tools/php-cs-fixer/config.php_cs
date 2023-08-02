<?php

$config = new PhpCsFixer\Config();

return $config
  ->setRules([
      '@PSR12' => true,
      '@PHP81Migration' => true,

      // document, global

      'no_extra_blank_lines' => true,
      'no_useless_else' => true,
      'no_useless_return' => true,
      'single_quote' => true,
      'no_whitespace_in_blank_line' => true,
      'single_blank_line_at_eof' => true,

      'binary_operator_spaces' => true,
      'cast_spaces' => true,
      'ternary_to_null_coalescing' => true,
      'no_spaces_inside_parenthesis' => true,
      'unary_operator_spaces' => true,
      'operator_linebreak' => true,
      'ternary_operator_spaces' => true,
      'ternary_to_null_coalescing' => true,
      'single_space_after_construct' => true,

      // <?php

      'blank_line_after_opening_tag' => true,
      'linebreak_after_opening_tag' => true,

      // namespace

      'no_unused_imports' => true,
      'ordered_imports' => true,

      // class, phpdoc

      'no_blank_lines_after_class_opening' => true,
      'class_attributes_separation' => ['elements' => ['method' => 'one']],

      'no_blank_lines_after_phpdoc' => true,
      'phpdoc_indent' => false,
      'phpdoc_to_comment' => true,
      'phpdoc_trim' => true,

      // method

      'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
      'method_chaining_indentation' => true,

      // array

      'array_syntax' => ['syntax' => 'short'],
      'array_indentation' => true,
      'trim_array_spaces' => true,
      'no_trailing_comma_in_singleline_array' => true,
      'trailing_comma_in_multiline' => false,
      'no_spaces_around_offset' => true,
      'no_whitespace_before_comma_in_array' => true,
      'whitespace_after_comma_in_array' => true,

      // string

      'concat_space' => [ 'spacing' => 'one' ],
      'explicit_string_variable' => true,
      'simple_to_complex_string_variable' => true,

      // switch

      'no_break_comment' => false
  ])
  ->setUsingCache(false);
