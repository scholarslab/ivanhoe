require 'csv'
require 'date'

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
  #
  # (March 4)
  # - 0.443992
  # - 0.456232
  # - 0.50325
  #
  # (direct inserting, March 4)
  # - 0.036109
  # - 0.0266
  # - 0.03718

  # NB: When you use this, make sure you access the page again *after* creating
  # the game, before testing the expectation. Otherwise, you'll be looking at
  # an old page.
  def make_game(user_login='admin')
    post_title   = Faker::Lorem.words(rand(2..8)).join(' ')
    post_content = Faker::Lorem.paragraphs(rand(3..10)).join('<p>')
    author       = WPDB::User.first(:user_login => user_login)
    WPDB::Post.create(
      :post_title            => post_title,
      :post_content          => post_content,
      :author                => author,
      :post_excerpt          => '',
      :to_ping               => '',
      :pinged                => '',
      :post_content_filtered => '',
      :post_status           => 'publish',
      :post_type             => 'ivanhoe_game',
      :comment_status        => 'closed',
    )
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
