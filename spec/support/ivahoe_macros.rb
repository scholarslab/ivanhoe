module IvanhoeMacros

  @valid_game = {
    :game_title => FFaker::Lorem.words(rand(2..8)),
    :game_description => FFaker::Lorem.paragraphs(rand(1..3))
  }

  def self.tiny_mce_fill_in(name, args)
    page.execute_script("tinymce.editors[0].setContent('#{args[:with]}')")
  end

  def self.login_as_admin
    vist URL_BASE
    click_link('Log In')
    fill_in 'Username', with: ENV['VALID_PASSWORD']
    fill_in 'Password', with: ENV['VALID_PASSWORD']
    click_button 'Log In'
  end

  def self.create_new_game
    login_as_admin
    click_link 'Make a Game'
    fill_in 'post_title', :with => @valid_game[:game_title]
    tiny_mce_fill_in 'post_content', :with => @valid_game[:game_description]
  end


end
