require 'spec_helper'

describe 'Single Role View', :type => :feature, :js => true do

    include ApplicationHelper

    before(:each) do
        visit(URL_BASE)
        login
        game = make_game
        make_role(game_id: game)
        visit(URL_BASE + "/?ivanhoe_game=#{game.post_name}")
        make_a_move
        within('article.role') do
            first('a').click
        end
    end

    it 'has a linked role title' do
        within('.role') do
            expect(page).to have_selector('h1 a')
        end
    end

    it 'has the role image' do
        within('.role') do
            expect(page).to have_selector('img')
        end
    end

    it 'has a role description' do
      within('.role') do
        expect(page).to have_selector('p')
      end
    end

    it 'has a return to game link' do
      within('.role') do
        expect(page).to have_link('Return to game')
        expect(page).to have_selector('.return-btn')
      end
    end

    it 'has a Moves list' do
        within('.moves') do
            expect(page).to have_content('Moves')
            expect(page).to have_selector('ul')
        end
    end

    it 'has linked move titles' do
      within('.moves') do
        expect(page).to have_selector('li a', :match => :first)
      end
    end

    it 'has a Rationales list' do
        within('.rationales') do
            expect(page).to have_content('Rationales')
            expect(page).to have_selector('ul')
        end
    end

    it 'has linked rationale titles' do
      within('.rationales') do
        expect(page).to have_selector('li a', :match => :first)
        expect(page).to have_content('Journal Entry', :match => :first)
      end
    end

end
