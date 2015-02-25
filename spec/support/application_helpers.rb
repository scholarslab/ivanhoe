require 'csv'

module ApplicationHelper

  def dump_table(dump_dir, table_name)
    output  = "#{dump_dir}/#{table_name}.csv"
    results = @cxn[table_name.to_sym].all
    CSV.open(output, 'wb', :write_headers => true) do |csv|
      unless results.empty?
        fields = results.first.keys.sort
        csv << fields
        results.each do |row|
          csv << fields.map { |key| row[key] }
        end
      end
    end
  end

  def db_dump
    now      = Time.new
    dump_dir = "db-dump-#{now.strftime '%Y%m%d-%H%M%S.%L'}"
    Dir.mkdir dump_dir

    @cxn["SHOW TABLES;"]
      .map    { |row| row[:Tables_in_test_ivanhoe] }
      .reject { |table| table.start_with? "copy_wp" }
      .each   { |table| dump_table dump_dir, table }
  end


  def tiny_mce_fill_in(name, args)
    page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
  end

  def tiny_mce_fill_in_post_content(name, args)
    page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
  end

  def tiny_mce_fill_in_post_rationale(name, args)
    page.execute_script("tinymce.editors[1].setContent('#{args[:with]}')")
  end

  def login
    click_link('Log in')
    fill_in 'Username', with: 'admin'
    fill_in 'Password', with: 'admin'
    click_button 'Log In'
  end

  def login_editor
    click_link('Log in')
    fill_in 'Username', with: 'editor'
    fill_in 'Password', with: 'editor'
    click_button 'Log In'
  end

  # Timings:
  # - 0.552375
  # - 0.516728
  # - 0.551325
  # mean = 0.540142667
  #
  # (From run on Feb 25):
  # - 1.22162
  # - 1.3177
  # - 1.288371
  def make_game
    db_dump
    # results = @cxn.query("SELECT MAX(ID)+1 AS row_id FROM wp_posts;")
    # row_id = results.to_a.first['row_id']

    # # TODO: also call @cxn.escape on all of these (and the guid)
    # # TODO: Am I sure I don't want to just use sequel?
    # guid = "#{BASE_URL}/?post_type=ivanhoe_game&p=#{row_id}"
    # post_author = nil
    # post_content = nil
    # post_content_filtered = nil

    # HEREIAM: the data in this line appears to be the autosave data. I've
    # added the sleep below, so hopefully re-running it should now output the
    # complete saved entry.

    # @cxn.query(<<END
      # INSERT INTO wp_posts
        # (ID,comment_count,comment_status,guid,menu_order,ping_status,pinged,
        # post_author,post_content,post_content_filtered,
        # post_date,post_date_gmt,post_excerpt,post_mime_type,
        # post_modified,post_modified_gmt,
        # post_name,
        # post_parent,post_password,post_status,post_title,post_type,to_ping)
        # VALUES
        # (#{row_id},0,'open',#{guid},0,'open','',
        # #{post_author},#{post_content},#{post_content_filtered},
        # #{now},#{now_gmt},'','',
        # #{now},#{now_gmt},'',0,'',auto-draft,Auto Draft,ivanhoe_game,''
# END
# )

    click_link 'Make a Game'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'

    sleep 0.5
    db_dump
  end

  def make_role
    click_link('Make a Role!')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..4)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    attach_file('post_thumbnail', 'spec/dumps/puppy.jpg')
    click_button 'Save'
  end

  def make_a_move
    click_button 'Make a Move'
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

  def respond_to_move
    click_link('Respond')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

  def main_page_respond_to_move
    first('.new_source').click
    click_button('Respond')
    fill_in 'post_title', :with => Faker::Lorem.words(rand(2..8)).join(' ')
    tiny_mce_fill_in_post_content('post_content', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    tiny_mce_fill_in_post_rationale('post_rationale', :with => Faker::Lorem.paragraphs(rand(3..10)).join('<p>'))
    click_button 'Save'
  end

end
