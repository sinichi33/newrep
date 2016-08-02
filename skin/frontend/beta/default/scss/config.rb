# Note: this should never truly be referenced since we are using relative assets
http_path = "/skin/frontend/beta/default"
css_dir = "../css"
sass_dir = "../scss"
images_dir = "../images"
javascripts_dir = "../js"
relative_assets = true

add_import_path "../../../rwd/enterprise/scss"
add_import_path "../../../rwd/default/scss"

output_style = :expanded
environment = :development

#----------------------------------------------------------------------------
# Fix for IE selector limit.
# IE9 (and lower) have stylesheet rule limits of 4095.
# Split css into additional files (to be included <= IE9 only). Main stylesheet will contain all styles and is compatible with major browsers.
# https://gist.github.com/10thfloor/3559165
# http://stackoverflow.com/questions/9906794/internet-explorers-css-rules-limits
#----------------------------------------------------------------------------

on_stylesheet_saved do |path|
  CssSplitter.split(path) unless path[/\d+$/]
end

class CssSplitter

  def self.split(infile, outdir = File.dirname(infile), max_selectors = 4095)

    raise "infile could not be found" unless File.exists? infile

    rules = IO.readlines(infile, "}")
    return if rules.first.nil?

    charset_statement, rules[0] = rules.first.partition(/^\@charset[^;]+;/)[1,2]
    return if rules.nil?

    # The infile remains the first file
    file_id = 1
    selectors_count = 0
    output = nil

    rules.each do |rule|
      rule_selectors_count = count_selectors_of_rule rule
      selectors_count += rule_selectors_count

      # Nothing happens until the selectors limit is reached for the first time
      if selectors_count > max_selectors
        # Close current file if there is already one
        output.close if output

        # Prepare next file
        file_id += 1
        filename = File.join(outdir, File.basename(infile, File.extname(infile)) + "_#{file_id.to_s}" +    File.extname(infile))
        output = File.new(filename, "w")
        output.write charset_statement

        # Reset count with current rule count
        selectors_count = rule_selectors_count
      end

      output.write rule if output
    end
  end

  def self.count_selectors(css_file)
    raise "file could not be found" unless File.exists? css_file

    rules = IO.readlines(css_file, '}')
    return if rules.first.nil?

    charset_statement, rules[0] = rules.first.partition(/^\@charset[^;]+;/)[1,2]
    return if rules.first.nil?

    rules.inject(0) {|count, rule| count + count_selectors_of_rule(rule)}.tap do |result|
      puts File.basename(css_file) + " contains #{result} selectors."
    end
  end

  def self.count_selectors_of_rule(rule)
    rule.partition(/\{/).first.scan(/,/).count.to_i + 1
  end

end